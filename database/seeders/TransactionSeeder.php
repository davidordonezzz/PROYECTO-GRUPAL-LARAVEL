<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        Transaction::factory()->count(15)->create()->each(function ($transaction) use ($products) {
            // Cada transacción tiene entre 1 y 3 items
            $numItems = rand(1, min(3, $products->count()));
            $selectedProducts = $products->random($numItems);
            $total = 0;

            foreach ($selectedProducts as $product) {
                $quantity  = rand(1, 3);
                $unitPrice = $product->price;
                $subtotal  = round($quantity * $unitPrice, 2);
                $total    += $subtotal;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'quantity'        => $quantity,
                    'unit_price'      => $unitPrice,
                    'subtotal'        => $subtotal,
                ]);
            }

            // Actualizar el total real de la transacción
            $transaction->update(['amount' => $total]);
        });
    }
}
