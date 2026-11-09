<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowerImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['flower_id', 'path', 'is_primary', 'sort_order'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function flower(): BelongsTo
    {
        return $this->belongsTo(Flower::class);
    }
}