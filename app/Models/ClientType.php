<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    protected $fillable = ['name', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
