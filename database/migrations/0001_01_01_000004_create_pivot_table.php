<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Nonaktifkan sementara pemeriksaan foreign key agar tidak error saat pembuatan tabel
        Schema::disableForeignKeyConstraints();

        Schema::create('flower_occasion', function (Blueprint $table) {
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained('occasions')->cascadeOnDelete();
            $table->primary(['flower_id', 'occasion_id']);
        });

        Schema::create('flower_color', function (Blueprint $table) {
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->foreignId('color_id')->constrained('colors')->cascadeOnDelete();
            $table->primary(['flower_id', 'color_id']);
        });

        Schema::create('flower_flower_type', function (Blueprint $table) {
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->foreignId('flower_type_id')->constrained('flower_types')->cascadeOnDelete();
            $table->primary(['flower_id', 'flower_type_id']);
        });

        Schema::create('flower_tag', function (Blueprint $table) {
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['flower_id', 'tag_id']);
        });

        Schema::create('branch_flower', function (Blueprint $table) {
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('flower_id')->constrained('flowers')->cascadeOnDelete();
            $table->boolean('is_available')->default(true);
            $table->primary(['branch_id', 'flower_id']);
        });

        // Aktifkan kembali pemeriksaan foreign key
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('branch_flower');
        Schema::dropIfExists('flower_tag');
        Schema::dropIfExists('flower_flower_type');
        Schema::dropIfExists('flower_color');
        Schema::dropIfExists('flower_occasion');
        Schema::enableForeignKeyConstraints();
    }
};