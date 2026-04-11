<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisProyek extends Model
{
    protected $table = 'jenis_proyek';
    protected $fillable = ['nama', 'warna', 'ikon', 'urutan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function pengalamans()
    {
        return $this->hasMany(PengalamanProyek::class, 'jenis_proyek_id')->orderBy('urutan')->orderBy('id');
    }

    public function pengalamanAktif()
    {
        return $this->hasMany(PengalamanProyek::class, 'jenis_proyek_id')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class, 'jenis_proyek_id');
    }

    public function activeProjects()
    {
        return $this->hasMany(\App\Models\Project::class, 'jenis_proyek_id')
            ->where('is_active', true)
            ->orderByDesc('year')
            ->orderBy('name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan')->orderBy('id');
    }
}
