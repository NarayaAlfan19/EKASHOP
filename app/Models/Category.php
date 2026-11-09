<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $timestamps = false; // Karena di migrasi tidak ada created_at/updated_at

    protected $fillable = ['name', 'slug'];

    public function flowers(): HasMany
    {
        return $this->hasMany(Flower::class);
    }
}