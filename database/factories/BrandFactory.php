<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        // Marcas reales de hardware para que los datos sean coherentes
        $brands = [
            'ASUS', 'MSI', 'Gigabyte', 'EVGA', 'Corsair', 'Kingston',
            'Logitech', 'Razer', 'Samsung', 'Seagate', 'Western Digital',
            'AMD', 'Intel', 'NVIDIA', 'Cooler Master', 'NZXT',
            'HyperX', 'Thermaltake', 'be quiet!', 'Sapphire',
        ];

        $name = $this->faker->unique()->randomElement($brands);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'logo_url'    => null,
            'active'      => true,
        ];
    }
}
