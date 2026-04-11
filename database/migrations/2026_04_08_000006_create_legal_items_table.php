<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('value');
            $table->string('icon')->default('fas fa-file-alt');
            $table->integer('order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_items');
    }
};
