<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
    'price', 'bulk_price', 'bulk_min_quantity', 'compare_at_price', 'stock', 'size_label', 'image_url',
    'primary_media_id', 'gallery_media_ids', 'ingredients', 'directions', 'precautions', 'barcode',
    'weight_grams', 'package_length_cm', 'package_width_cm', 'package_height_cm', 'seo_title', 'seo_description',
    'amazon_asin', 'amazon_url', 'amazon_status', 'amazon_title', 'amazon_description',
    'mercadolibre_item_id', 'mercadolibre_url', 'mercadolibre_status', 'mercadolibre_title', 'mercadolibre_description',
    'is_featured', 'is_active',
])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'bulk_price' => 'decimal:2',
            'bulk_min_quantity' => 'integer',
            'compare_at_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'gallery_media_ids' => 'array',
        ];
    }

    public function unitPriceForQuantity(int $quantity): float
    {
        return $this->bulk_price !== null && $this->bulk_min_quantity !== null && $quantity >= $this->bulk_min_quantity
            ? (float) $this->bulk_price
            : (float) $this->price;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('category', fn (Builder $category) => $category->where('is_active', true));
    }

    public function primaryMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'primary_media_id');
    }

    public function displayImageUrl(): string
    {
        return $this->primaryMedia?->image()?->getUrl() ?? $this->image_url ?? asset('images/brand/product-cutout.jpeg');
    }

    public function displayImageAlt(): string
    {
        return $this->primaryMedia?->alt_text ?? $this->name;
    }

    public function displayFocalPoint(): string
    {
        return ($this->primaryMedia?->focal_x ?? 50).'% '.($this->primaryMedia?->focal_y ?? 50).'%';
    }
}
