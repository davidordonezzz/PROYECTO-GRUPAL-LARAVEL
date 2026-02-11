<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        Product::factory()->count(25)->create()->each(function ($product) use ($categories) {
            // Asignar 1-3 categorías aleatorias a cada producto (pivote n:m)
            $product->categories()->attach(
                $categories->random(rand(1, min(3, $categories->count())))->pluck('id')
            );
        });
    }
}
