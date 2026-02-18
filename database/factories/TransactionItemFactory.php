<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        $quantity  = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->randomFloat(2, 29.99, 999.99);

        return [
            'transaction_id' => Transaction::inRandomOrder()->first()?->id ?? Transaction::factory(),
            'product_id'     => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'quantity'        => $quantity,
            'unit_price'      => $unitPrice,
            'subtotal'        => round($quantity * $unitPrice, 2),
        ];
    }
}
