<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flowers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('price');
            $table->integer('discount_price')->nullable();
            $table->string('size')->nullable();
            $table->enum('status', ['draft', 'active', 'sold_out'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('flower_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
        });
    }

    public function down(): void {
        Schema::dropIfExists('flower_images');
        Schema::dropIfExists('flowers');
    }
};