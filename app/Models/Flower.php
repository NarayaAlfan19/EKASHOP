<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Flower extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 
        'discount_price', 'size', 'status', 'is_featured', 
        'meta_title', 'meta_description'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'integer',
        'discount_price' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(FlowerImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(FlowerImage::class)->where('is_primary', true);
    }

    public function occasions(): BelongsToMany
    {
        return $this->belongsToMany(Occasion::class, 'flower_occasion');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'flower_color');
    }

    public function flowerTypes(): BelongsToMany
    {
        return $this->belongsToMany(FlowerType::class, 'flower_flower_type');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'flower_tag');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_flower')->withPivot('is_available');
    }

    public function inquiryItems(): HasMany
    {
        return $this->hasMany(InquiryItem::class);
    }
}