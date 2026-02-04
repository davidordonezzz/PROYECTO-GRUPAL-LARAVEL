<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'currency',
        'shipping_address',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relación 1:n (order pertenece a user)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación 1:n con order_items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relación 1:1 con payment
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Relación n:m con products (a través de order_items)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }
}
