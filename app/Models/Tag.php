<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'type'];

    public function flowers(): BelongsToMany
    {
        return $this->belongsToMany(Flower::class, 'flower_tag');
    }
}