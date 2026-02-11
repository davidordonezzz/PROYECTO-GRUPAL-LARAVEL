<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'payer_email',
        'amount',
        'currency',
        'status',
        'payment_method',
        'paypal_response',
        'paid_at',
    ];

    protected $casts = [
        'paypal_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
