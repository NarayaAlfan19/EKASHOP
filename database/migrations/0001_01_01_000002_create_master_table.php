<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
        });

        Schema::create('occasions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
        });

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex'); // format #RRGGBB
        });

        Schema::create('flower_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['keyword', 'mood']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('maps_url');
            $table->string('whatsapp_number')->nullable();
            $table->string('opening_hours');
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void {
        Schema::dropIfExists('branches');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('flower_types');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('occasions');
        Schema::dropIfExists('categories');
    }
};