<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'address', 'maps_url', 'whatsapp_number', 'opening_hours', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function flowers(): BelongsToMany
    {
        return $this->belongsToMany(Flower::class, 'branch_flower')->withPivot('is_available');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}