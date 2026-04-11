<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('warna', 30)->default('primary'); // e.g. primary, warning, success, danger, info
            $table->string('ikon', 80)->default('fas fa-folder');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_proyek');
    }
};
