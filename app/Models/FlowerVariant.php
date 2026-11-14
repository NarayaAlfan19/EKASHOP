<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowerVariant extends Model
{
    protected $fillable = ['flower_id', 'name', 'price', 'image'];

    public function flower(): BelongsTo
    {
        return $this->belongsTo(Flower::class);
    }
}
