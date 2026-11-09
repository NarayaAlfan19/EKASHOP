<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::disableForeignKeyConstraints();

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('customer_name');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->dateTime('pickup_at');
            $table->string('note', 200)->nullable();
            $table->integer('total');
            $table->text('wa_url');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('inquiry_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inquiry_id');
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
            
            // Dibuat manual secara eksplisit agar aman dari error errno: 150
            $table->unsignedBigInteger('flower_id')->nullable();
            $table->foreign('flower_id')->references('id')->on('flowers')->nullOnDelete();
            
            $table->string('flower_name'); // Snapshot nama produk
            $table->integer('price');      // Snapshot harga saat dibeli
            $table->integer('qty');
            $table->integer('subtotal');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('inquiry_items');
        Schema::dropIfExists('inquiries');
        Schema::enableForeignKeyConstraints();
    }
};