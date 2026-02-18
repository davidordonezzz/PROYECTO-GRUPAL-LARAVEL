<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $tags       = Tag::all();

        Product::factory()->count(25)->create()->each(function ($product) use ($categories, $tags) {
            // Asignar 1-3 categorías aleatorias
            $product->categories()->attach(
                $categories->random(rand(1, min(3, $categories->count())))->pluck('id')
            );

            // Asignar 1-3 tags aleatorios
            if ($tags->count() > 0) {
                $product->tags()->attach(
                    $tags->random(rand(1, min(3, $tags->count())))->pluck('id')
                );
            }
        });
    }
}
