<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengalamanProyek extends Model
{
    protected $table = 'pengalaman_proyek';
    protected $fillable = ['jenis_proyek_id', 'project_id', 'nama', 'deskripsi', 'gambar', 'galeri', 'lokasi', 'tahun', 'pemberi_tugas', 'urutan', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'galeri' => 'array'];

    public function jenis()
    {
        return $this->belongsTo(JenisProyek::class, 'jenis_proyek_id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan')->orderBy('id');
    }
}
