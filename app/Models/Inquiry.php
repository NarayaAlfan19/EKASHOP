<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends Model
{
    public $timestamps = false; // Hanya pakai created_at manual

    protected $fillable = [
        'code', 'customer_name', 'branch_id', 'pickup_at', 'note', 'total', 'wa_url'
    ];

    protected $casts = [
        'pickup_at' => 'datetime',
        'created_at' => 'datetime',
        'total' => 'integer',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InquiryItem::class);
    }
}