<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'hex'];

    public function flowers(): BelongsToMany
    {
        return $this->belongsToMany(Flower::class, 'flower_color');
    }
}