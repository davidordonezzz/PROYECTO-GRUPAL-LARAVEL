<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            ['name' => 'ASUS', 'country' => 'Taiwán', 'website' => 'https://www.asus.com'],
            ['name' => 'MSI', 'country' => 'Taiwán', 'website' => 'https://www.msi.com'],
            ['name' => 'Gigabyte', 'country' => 'Taiwán', 'website' => 'https://www.gigabyte.com'],
            ['name' => 'Corsair', 'country' => 'Estados Unidos', 'website' => 'https://www.corsair.com'],
            ['name' => 'Logitech', 'country' => 'Suiza', 'website' => 'https://www.logitech.com'],
            ['name' => 'Razer', 'country' => 'Estados Unidos', 'website' => 'https://www.razer.com'],
            ['name' => 'Kingston', 'country' => 'Estados Unidos', 'website' => 'https://www.kingston.com'],
            ['name' => 'Samsung', 'country' => 'Corea del Sur', 'website' => 'https://www.samsung.com'],
            ['name' => 'Intel', 'country' => 'Estados Unidos', 'website' => 'https://www.intel.com'],
            ['name' => 'AMD', 'country' => 'Estados Unidos', 'website' => 'https://www.amd.com'],
            ['name' => 'NVIDIA', 'country' => 'Estados Unidos', 'website' => 'https://www.nvidia.com'],
            ['name' => 'Seagate', 'country' => 'Estados Unidos', 'website' => 'https://www.seagate.com'],
            ['name' => 'Western Digital', 'country' => 'Estados Unidos', 'website' => 'https://www.westerndigital.com'],
            ['name' => 'HP', 'country' => 'Estados Unidos', 'website' => 'https://www.hp.com'],
            ['name' => 'Dell', 'country' => 'Estados Unidos', 'website' => 'https://www.dell.com'],
            ['name' => 'Lenovo', 'country' => 'China', 'website' => 'https://www.lenovo.com'],
            ['name' => 'Acer', 'country' => 'Taiwán', 'website' => 'https://www.acer.com'],
            ['name' => 'BenQ', 'country' => 'Taiwán', 'website' => 'https://www.benq.com'],
            ['name' => 'LG', 'country' => 'Corea del Sur', 'website' => 'https://www.lg.com'],
            ['name' => 'SteelSeries', 'country' => 'Dinamarca', 'website' => 'https://steelseries.com'],
        ];

        $brand = fake()->unique()->randomElement($brands);

        return [
            'name' => $brand['name'],
            'slug' => Str::slug($brand['name']),
            'country' => $brand['country'],
            'website' => $brand['website'],
            'logo_url' => null,
            'active' => true,
        ];
    }
}
