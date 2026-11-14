<?php

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
    Schema::create('flower_variants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('flower_id')->constrained('flowers')->onDelete('cascade');
        $table->string('name'); // Contoh: "A", "B", "C" atau "Small", "Medium"
        $table->decimal('price', 12, 2); // Harga khusus varian ini
        $table->string('image')->nullable(); // Foto spesifik untuk varian ini
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flower_variants');
    }
};
