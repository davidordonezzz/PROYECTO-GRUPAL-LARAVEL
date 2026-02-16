<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'stock',
        'image_url',
        'image_public_id',
        'active',
        'brand_id',
    ];

    protected function casts(): array
    {
        return [
            'price'  => 'decimal:2',
            'stock'  => 'integer',
            'active' => 'boolean',
        ];
    }

    // ── Relaciones ──────────────────────────────────────

    // 1:n → Un producto pertenece a una marca
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // n:m → Un producto tiene muchas categorías
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    // n:m → Un producto tiene muchos tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // ── Scopes ──────────────────────────────────────────

    /**
     * Filtrar por búsqueda (nombre o SKU)
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Filtrar por marca
     */
    public function scopeByBrand($query, $brandId)
    {
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        return $query;
    }

    /**
     * Filtrar por categoría
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        return $query;
    }
}
