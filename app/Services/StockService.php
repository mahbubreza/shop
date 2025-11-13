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
     * Reserve stock and create a pending order
     *
     * @param array $cartItems
     * @param int $userId
     * @return Order
     * @throws Exception
     */
    public function reserveStock(array $cartItems, int $userId)
    {
        return DB::transaction(function () use ($cartItems, $userId) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw new Exception("Product ID {$item['product_id']} not found");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Not enough stock for {$product->name}");
                }

                // Reduce stock temporarily
                $product->stock -= $item['quantity'];
                $product->save();

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });
    }

    /**
     * Confirm stock after successful payment
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function confirmStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);
            if (!$order) throw new Exception("Order not found");

            $order->status = 'completed';
            $order->save();

            return $order;
        });
    }

    /**
     * Release stock on payment failure
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function releaseStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->find($orderId);
            if (!$order) throw new Exception("Order not found");

            // Restore stock
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->save();

            return $order;
        });
    }
}
