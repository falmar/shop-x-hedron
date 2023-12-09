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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            // skip creating brand table, simply use string
            $table->string('brand');
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->integer('price')->default(0); # in cents like stripe
            $table->integer('stock')->default(0);
            $table->integer('review_count')->default(0);
            $table->decimal('review_rating', 1, 2)->default(0.0);

            $table->timestamps(3);
            $table->softDeletes('deleted_at', 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
