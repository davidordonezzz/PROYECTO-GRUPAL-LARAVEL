<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Tarjetas Gráficas', 'Procesadores', 'Memoria RAM',
            'Discos Duros', 'SSD', 'Placas Base', 'Fuentes de Alimentación',
            'Cajas/Torres', 'Refrigeración', 'Monitores',
            'Teclados', 'Ratones', 'Auriculares', 'Portátiles', 'Periféricos',
        ];

        $name = $this->faker->unique()->randomElement($categories);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => $this->faker->sentence(8),
            'icon'        => null,
            'active'      => true,
        ];
    }
}
