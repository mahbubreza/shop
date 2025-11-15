<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Reserve stock and create a pending order.
     *
     * @param array $cartItems Each item: ['product_id'=>int,'quantity'=>int,'price'=>float]
     * @param int $userId
     * @return Order
     * @throws Exception
     */
    public function reserveStock(array $cartItems, int $userId, int $orderId)
    {
        return DB::transaction(function () use ($cartItems, $userId, $orderId) {
            foreach ($cartItems as $ci) {
                $product = Product::lockForUpdate()->find($ci['product_id']);
                if (!$product) throw new Exception("Product ID {$ci['product_id']} not found.");

                // Double-check availability
                $available = $product->stock - $product->reserved_stock;
                if ($ci['quantity'] > $available) {
                    throw new Exception("Not enough stock for {$product->name} (race condition).");
                }

                // Reserve stock
                $product->reserved_stock += $ci['quantity'];
                $product->save();

                // Create order item
                OrderItem::create([
                    'order_id'   => $orderId,
                    'product_id' => $product->id,
                    'quantity'   => $ci['quantity'],
                    'price'      => $ci['price'],
                ]);
            }

            return Order::find($orderId);
        });
    }


    /**
     * Confirm stock after successful payment:
     * - Decrease reserved_stock
     * - Decrease actual stock (sold)
     * - Mark order completed
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function confirmStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->lockForUpdate()->find($orderId);

            if (!$order) {
                throw new Exception("Order not found");
            }

            if ($order->status !== 'pending') {
                throw new Exception("Order #{$order->id} cannot be confirmed. Current status: {$order->status}");
            }

            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product) {
                    throw new Exception("Product ID {$item->product_id} not found while confirming order.");
                }

                // Ensure reserved_stock >= quantity
                if ($product->reserved_stock < $item->quantity) {
                    throw new Exception("Reserved stock inconsistency for {$product->name}.");
                }

                // Reduce reserved_stock and actual stock (finalize sale)
                $product->reserved_stock = (int)$product->reserved_stock - (int)$item->quantity;
                $product->stock = (int)$product->stock - (int)$item->quantity;

                if ($product->stock < 0) {
                    // This shouldn't happen if reserve checks worked, but guard anyway
                    throw new Exception("Stock cannot be negative for {$product->name}.");
                }

                $product->save();
            }

            $order->status = 'completed';
            $order->save();

            logger()->info('Order confirmed and stock updated', ['order_id' => $order->id]);


            return $order;
        });
    }

    /**
     * Release reserved stock when payment fails / order cancelled:
     * - Decrease reserved_stock
     * - Mark order cancelled
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function releaseStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->lockForUpdate()->find($orderId);

            if (!$order) {
                throw new Exception("Order not found");
            }

            if ($order->status !== 'pending') {
                throw new Exception("Order #{$order->id} cannot be cancelled. Current status: {$order->status}");
            }

            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    // Reduce reserved_stock but never below zero
                    $product->reserved_stock = max(0, (int)$product->reserved_stock - (int)$item->quantity);
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->save();

            \Log::info("Order cancelled and reserved stock released", ['order_id' => $order->id]);

            return $order;
        });
    }
}
