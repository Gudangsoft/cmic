<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['client_type_id', 'name', 'logo', 'website', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
