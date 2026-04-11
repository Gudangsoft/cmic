<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'client', 'client_id', 'location', 'year', 'jenis_proyek_id', 'category', 'description', 'image', 'gallery', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'gallery' => 'array'];

    public function jenisProyek()
    {
        return $this->belongsTo(JenisProyek::class, 'jenis_proyek_id');
    }

    public function clientModel()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->latest();
    }
}
