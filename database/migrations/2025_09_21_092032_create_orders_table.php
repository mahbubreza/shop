<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User placing the order
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            // Coupon fields
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);

            // Order amounts
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('mfs_charge', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            

            // Customer info
            $table->string('mobile_number')->nullable();
            $table->string('shipping_address')->nullable();

            // Order status
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered

            $table->text('admin_note')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('payment_provider')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
