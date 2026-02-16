<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $products = [
            'RTX 4090 Gaming OC', 'RTX 4080 Super', 'RX 7900 XTX',
            'Ryzen 9 7950X', 'Core i9-14900K', 'Ryzen 7 7800X3D',
            'DDR5 32GB 6000MHz', 'DDR5 16GB 5600MHz', 'DDR4 32GB 3600MHz',
            'SSD NVMe 2TB Gen4', 'SSD NVMe 1TB Gen5', 'HDD 4TB Barracuda',
            'ROG Strix Z790-E', 'MPG B650 Carbon', 'AORUS Elite AX',
            'RM850x PSU', 'HX1200 Platinum', 'Kraken X73 AIO',
            'Dark Rock Pro 5', 'H510 Elite Tower', 'Lancool III Mesh',
            'Odyssey G9 49"', 'PG27AQN 360Hz', 'K100 RGB Keyboard',
            'DeathAdder V3 Pro', 'Cloud III Headset', 'G Pro X Superlight',
            'Alienware x17 R2', 'ROG Zephyrus G16', 'Raider GE78 HX',
        ];

        $name = $this->faker->unique()->randomElement($products);

        return [
            'name'            => $name,
            'slug'            => Str::slug($name),
            'sku'             => strtoupper($this->faker->unique()->bothify('???-#####')),
            'description'     => $this->faker->paragraph(3),
            'price'           => $this->faker->randomFloat(2, 29.99, 2499.99),
            'stock'           => $this->faker->numberBetween(0, 100),
            'image_url'       => null,
            'image_public_id' => null,
            'active'          => $this->faker->boolean(85),
            'brand_id'        => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
        ];
    }
}
