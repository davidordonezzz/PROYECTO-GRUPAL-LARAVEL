<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Teclado mecánico RGB',
            'Ratón gaming inalámbrico',
            'Monitor gaming 27" 144Hz',
            'Tarjeta gráfica RTX 4070',
            'Procesador Ryzen 7 7800X3D',
            'Memoria RAM DDR5 32GB',
            'SSD NVMe 1TB',
            'Placa base ATX Z790',
            'Fuente modular 850W 80+ Gold',
            'Torre gaming RGB',
            'Auriculares gaming 7.1',
            'Webcam 4K con micrófono',
            'Alfombrilla XXL RGB',
            'Refrigeración líquida 360mm',
            'Ventilador ARGB 120mm',
            'Hub USB 3.0 7 puertos',
            'Disco duro externo 4TB',
            'Router WiFi 6E',
            'Tarjeta de red PCIe',
            'Capturadora de video 4K',
            'Micrófono condensador USB',
            'Soporte monitor ajustable',
            'Regleta gaming 6 tomas',
            'Cable HDMI 2.1 2m',
            'Pasta térmica premium',
            'Kit limpieza PC',
            'Portátil gaming 15.6"',
            'Ordenador sobremesa gaming',
            'Mini PC compacto',
            'All-in-One 24"',
        ];

        $name = fake()->randomElement($products) . ' ' . fake()->bothify('??-###');

        return [
            'name' => $name,
            'sku' => strtoupper(Str::random(3)) . '-' . fake()->unique()->numerify('######'),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 19.99, 2499.99),
            'stock' => fake()->numberBetween(0, 100),
            'image_url' => null,
            'cloudinary_public_id' => null,
            'brand_id' => Brand::factory(),
            'active' => fake()->boolean(90), // 90% activos
        ];
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}
