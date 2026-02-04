<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'website',
        'logo_url',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relación 1:n con products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
