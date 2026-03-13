<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'label',
        'description',
        'price',
        'sales_count',
        'views_count',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function metadata(): HasMany
    {
        return $this->hasMany(ProductMetadata::class);
    }
}
