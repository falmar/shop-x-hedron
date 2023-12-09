<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('cart_id');
            $table->uuid('product_id');
            $table->integer('quantity')->default(1);
            $table->integer('price')->default(0); # in cents like stripe

            $table->timestamps(3);
            $table->softDeletes('deleted_at', 3);

            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
