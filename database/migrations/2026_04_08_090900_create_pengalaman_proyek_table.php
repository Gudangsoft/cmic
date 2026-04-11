<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengalaman_proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_proyek_id')->constrained('jenis_proyek')->onDelete('cascade');
            $table->string('nama');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman_proyek');
    }
};
