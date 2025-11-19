<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // add admin useful fields if they don't exist
            if (! Schema::hasColumn('orders', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('shipping_address');
            }

            if (! Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('admin_note');
            }

            if (! Schema::hasColumn('orders', 'payment_provider')) {
                $table->string('payment_provider')->nullable()->after('payment_method');
            }

            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['admin_note', 'tracking_number', 'payment_provider']);
        });
    }
};
