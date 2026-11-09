<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Occasion extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    public function flowers(): BelongsToMany
    {
        return $this->belongsToMany(Flower::class, 'flower_occasion');
    }
}