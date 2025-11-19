<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * List orders with filters, search and sorting
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);

        $query = Order::with(['items.product', 'user'])
            ->when($request->input('status'), fn($q, $s) => $q->where('status', $s))
            ->when($request->input('q'), function ($q, $qstr) {
                $q->where(function ($sub) use ($qstr) {
                    $sub->where('id', $qstr)
                        ->orWhere('mobile_number', 'like', "%{$qstr}%")
                        ->orWhere('shipping_address', 'like', "%{$qstr}%");
                });
            })
            ->when($request->input('from'), fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->input('to'), fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_dir', 'desc'));

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
     
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status (AJAX)
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {

        $old = $order->status;
        $status = $request->input('status');

        DB::beginTransaction();
        try {
            // If moving to completed -> confirm stock
            if ($old !== 'completed' && $status === 'completed') {
                // confirm stock via StockService
                $this->stockService->confirmStock($order->id);
            }

            // If moving to cancelled -> release stock
            if ($old === 'pending' && $status === 'cancelled') {
                $this->stockService->releaseStock($order->id);
            }

            $order->status = $status;
            if ($request->has('tracking_number')) {
                $order->tracking_number = $request->input('tracking_number');
            }
            $order->save();

            DB::commit();

            return response()->json(['success' => true, 'status' => $order->status]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order status change failed', ['order' => $order->id, 'err' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Add admin note
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate(['note' => 'nullable|string|max:2000']);

        $order->admin_note = $request->input('note');
        $order->save();

        return response()->json(['success' => true, 'admin_note' => $order->admin_note]);
    }

    /**
     * Export single order invoice placeholder (you can use dompdf/snappy)
     */
    public function exportInvoice(Request $request, Order $order)
    {

        // Simple CSV as example-of-export. Replace with PDF generation (dompdf) per your needs.
        $filename = "invoice_order_{$order->id}.csv";

        $response = new StreamedResponse(function () use ($order) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'User', 'Mobile', 'Subtotal', 'Shipping', 'MFS', 'VAT', 'Coupon', 'Discount', 'Total', 'Status']);
            fputcsv($handle, [
                $order->id,
                $order->user->name ?? $order->user_id,
                $order->mobile_number,
                $order->sub_total,
                $order->shipping_charge,
                $order->mfs_charge,
                $order->vat,
                $order->coupon_id,
                $order->coupon_discount,
                $order->total,
                $order->status
            ]);

            fputcsv($handle, []);
            fputcsv($handle, ['Items']);
            fputcsv($handle, ['Product', 'Qty', 'Price']);
            foreach ($order->items as $item) {
                fputcsv($handle, [$item->product->name ?? $item->product_id, $item->quantity, $item->price]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    /**
     * Export filtered order list (CSV)
     */
    public function exportList(Request $request)
    {
        Gate::authorize('viewAny', Order::class);

        $query = Order::query()
            ->when($request->input('status'), fn($q, $s) => $q->where('status', $s))
            ->when($request->input('from'), fn($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($request->input('to'), fn($q, $d) => $q->whereDate('created_at', '<=', $d));

        $filename = 'orders_export_' . now()->format('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID','User','Total','Status','Created At']);

            $query->chunk(200, function ($orders) use ($handle) {
                foreach ($orders as $o) {
                    fputcsv($handle, [$o->id, $o->user->name ?? $o->user_id, $o->total, $o->status, $o->created_at]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    /**
     * Bulk actions for the list (cancel, complete)
     */
    public function bulkAction(Request $request)
    {
        Gate::authorize('update', Order::class);
        $request->validate(['action' => 'required|string', 'ids' => 'required|array']);

        $action = $request->input('action');
        $ids = $request->input('ids');

        DB::beginTransaction();
        try {
            $orders = Order::whereIn('id', $ids)->lockForUpdate()->get();

            foreach ($orders as $order) {
                if ($action === 'cancel' && $order->status === 'pending') {
                    $this->stockService->releaseStock($order->id);
                    $order->status = 'cancelled';
                    $order->save();
                }

                if ($action === 'complete' && $order->status !== 'completed') {
                    $this->stockService->confirmStock($order->id);
                    $order->status = 'completed';
                    $order->save();
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk order action failed', ['err' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
