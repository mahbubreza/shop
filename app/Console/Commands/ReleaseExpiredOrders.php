<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Notifications\OrderExpiredNotification;
use App\Services\StockService;
use Carbon\Carbon;

class ReleaseExpiredOrders extends Command
{
    protected $signature = 'orders:release-expired';
    protected $description = 'Release reserved stock for abandoned (expired) pending orders';

    public function handle(StockService $stockService)
    {
        $this->info("Checking for expired orders...");

        // Expire time = 6 minutes (you can change)
        $expireBefore = Carbon::now()->subMinutes(6);

        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<', $expireBefore)
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info("No expired orders found.");
            return 0;
        }

        foreach ($expiredOrders as $order) {
            try {
                $stockService->releaseStock($order->id);

                // Optional: send user email
                 $order->user->notify(new OrderExpiredNotification($order));

                $this->info("Released stock for Order #{$order->id}");
            } catch (\Exception $e) {
                \Log::error("Error releasing stock for order: {$order->id}", [
                    'error' => $e->getMessage()
                ]);
                $this->error("Failed to release order #{$order->id}");
            }
        }

        $this->info("Expired order clean-up completed.");
        return 0;
    }
}
