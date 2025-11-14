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
     * @param array $cartItems
     * @param int $userId
     * @return Order
     * @throws Exception
     */
    public function reserveStock(array $cartItems, int $userId)
    {
        // Log cart items for debugging
        logger()->info('Reserving stock for user ID: ' . $userId, ['cartItems' => $cartItems]);

        return DB::transaction(function () use ($cartItems, $userId) {

            // Calculate total amount for the order
            $total = collect($cartItems)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // Create a new pending order
            $order = Order::create([
                'user_id' => $userId,
                'status'  => 'pending',
                'total'   => $total,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw new Exception("Product ID {$item['product_id']} not found");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Not enough stock for {$product->name}");
                }

                // Temporarily reduce stock
                $product->stock -= $item['quantity'];
                $product->save();

                // Create order item
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            logger()->info('Order created successfully', ['order_id' => $order->id, 'total' => $total]);

            return $order;
        });
    }

    /**
     * Confirm stock after successful payment.
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function confirmStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);

            if (!$order) {
                throw new Exception("Order not found");
            }

            // Prevent double payment
            if ($order->status !== 'pending') {
                throw new Exception("Order #{$order->id} cannot be confirmed. Current status: {$order->status}");
            }

            $order->status = 'completed';
            $order->save();

            logger()->info("Order confirmed", ['order_id' => $order->id]);

            return $order;
        });
    }

    /**
     * Release stock if payment fails or order is cancelled.
     *
     * @param int $orderId
     * @return Order
     * @throws Exception
     */
    public function releaseStock(int $orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->find($orderId);

            if (!$order) {
                throw new Exception("Order not found");
            }

            // Prevent cancelling a completed order
            if ($order->status !== 'pending') {
                throw new Exception("Order #{$order->id} cannot be cancelled. Current status: {$order->status}");
            }

            // Restore stock for each product
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->save();

            logger()->info("Order cancelled and stock released", ['order_id' => $order->id]);

            return $order;
        });
    }
}
