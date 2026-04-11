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
        Schema::table('pengalaman_proyek', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama');
            $table->string('gambar')->nullable()->after('deskripsi');
            $table->json('galeri')->nullable()->after('gambar');
            $table->string('lokasi')->nullable()->after('galeri');
            $table->year('tahun')->nullable()->after('lokasi');
            $table->string('pemberi_tugas')->nullable()->after('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('pengalaman_proyek', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'gambar', 'galeri', 'lokasi', 'tahun', 'pemberi_tugas']);
        });
    }
};
