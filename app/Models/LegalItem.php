<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalItem extends Model
{
    protected $fillable = ['label', 'value', 'icon', 'order', 'is_visible'];

    protected $casts = ['is_visible' => 'boolean'];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->orderBy('order');
    }
}
