<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'inquiry_id', 'flower_id', 'flower_name', 'price', 'qty', 'subtotal'
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function flower(): BelongsTo
    {
        return $this->belongsTo(Flower::class)->withTrashed();
    }
}