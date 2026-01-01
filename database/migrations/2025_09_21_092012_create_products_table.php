<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Foreign key to categories
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();

            // Product fields
            $table->string('name');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->string('weight')->nullable();
            $table->string('tags')->nullable();
            $table->string('youtube_link')->nullable();

            // File uploads
            $table->string('image')->nullable();         // Thumbnail
            $table->string('thumbnail_image')->nullable();         // Thumbnail
            $table->text('images')->nullable();          // Gallery images (JSON encoded)
            $table->text('videos')->nullable();          // Videos (JSON encoded)
            $table->text('pdfs')->nullable();            // PDFs (JSON encoded)

            // $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            // $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->decimal('discounted_price', 10, 2)->default(0);
            $table->date('discount_start_date')->nullable();
            $table->date('discount_end_date')->nullable();
            
            $table->integer('views')->default(0);
            $table->char('status', 1)->default('1');
            $table->char('featured', 1)->default('0');
            $table->char('published', 1)->default('0');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            $table->timestamps();
        });

        Schema::create('product_color', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Color::class)->constrained()->cascadeOnDelete();
            $table->char('status', 1)->default('1');

            $table->timestamps();
        });

        Schema::create('product_size', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Size::class)->constrained()->cascadeOnDelete();
            $table->char('status', 1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_color');
        Schema::dropIfExists('product_size');
    }
};
