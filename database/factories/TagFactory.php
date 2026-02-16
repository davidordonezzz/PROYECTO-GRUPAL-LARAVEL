<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $tags = [
            'Gaming'       => '#dc3545',
            'Oferta'       => '#fd7e14',
            'Nuevo'        => '#198754',
            'Reacondicionado' => '#6f42c1',
            'Top Ventas'   => '#0d6efd',
            'Edición Limitada' => '#d63384',
            'RGB'          => '#20c997',
            'Overclock'    => '#ffc107',
            'Silencioso'   => '#6c757d',
            'Eco'          => '#198754',
            'Profesional'  => '#0dcaf0',
            'Streaming'    => '#e91e63',
        ];

        $name  = $this->faker->unique()->randomElement(array_keys($tags));
        $color = $tags[$name];

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => $this->faker->sentence(8),
            'color'       => $color,
            'active'      => true,
        ];
    }
}
