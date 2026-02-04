<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'Ordenadores de sobremesa', 'description' => 'PCs de escritorio para gaming, oficina y uso doméstico'],
            ['name' => 'Portátiles', 'description' => 'Laptops para trabajo, estudio y entretenimiento'],
            ['name' => 'Componentes', 'description' => 'Piezas y hardware para montar o mejorar tu PC'],
            ['name' => 'Periféricos', 'description' => 'Teclados, ratones, auriculares y más'],
            ['name' => 'Monitores', 'description' => 'Pantallas para gaming, diseño y oficina'],
            ['name' => 'Almacenamiento', 'description' => 'Discos duros, SSDs y memorias USB'],
            ['name' => 'Tarjetas gráficas', 'description' => 'GPUs para gaming y trabajo profesional'],
            ['name' => 'Procesadores', 'description' => 'CPUs Intel y AMD para tu equipo'],
            ['name' => 'Memorias RAM', 'description' => 'Módulos de memoria para mejorar el rendimiento'],
            ['name' => 'Placas base', 'description' => 'Motherboards compatibles con los últimos procesadores'],
            ['name' => 'Fuentes de alimentación', 'description' => 'PSUs certificadas para tu sistema'],
            ['name' => 'Refrigeración', 'description' => 'Ventiladores y sistemas de refrigeración líquida'],
            ['name' => 'Cajas/Torres', 'description' => 'Chasis para montar tu PC'],
            ['name' => 'Redes', 'description' => 'Routers, switches y adaptadores de red'],
            ['name' => 'Gaming', 'description' => 'Productos especializados para gamers'],
        ];

        $category = fake()->unique()->randomElement($categories);

        return [
            'name' => $category['name'],
            'slug' => Str::slug($category['name']),
            'description' => $category['description'],
            'active' => true,
            'parent_id' => null,
        ];
    }
}
