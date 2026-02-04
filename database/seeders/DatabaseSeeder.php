<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tecnooutlet.com',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Crear usuarios normales
        User::factory(10)->create([
            'email_verified_at' => now(),
        ]);

        // Crear marcas específicas de informática
        $brandsData = [
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
            ['name' => 'Lenovo', 'country' => 'China', 'website' => 'https://www.lenovo.com'],
        ];

        foreach ($brandsData as $brandData) {
            Brand::create([
                'name' => $brandData['name'],
                'slug' => Str::slug($brandData['name']),
                'country' => $brandData['country'],
                'website' => $brandData['website'],
                'active' => true,
            ]);
        }

        // Crear categorías específicas
        $categoriesData = [
            ['name' => 'Ordenadores de sobremesa', 'description' => 'PCs de escritorio para gaming, oficina y uso doméstico'],
            ['name' => 'Portátiles', 'description' => 'Laptops para trabajo, estudio y entretenimiento'],
            ['name' => 'Componentes', 'description' => 'Piezas y hardware para montar o mejorar tu PC'],
            ['name' => 'Periféricos', 'description' => 'Teclados, ratones, auriculares y más'],
            ['name' => 'Monitores', 'description' => 'Pantallas para gaming, diseño y oficina'],
            ['name' => 'Almacenamiento', 'description' => 'Discos duros, SSDs y memorias USB'],
            ['name' => 'Tarjetas gráficas', 'description' => 'GPUs para gaming y trabajo profesional'],
            ['name' => 'Procesadores', 'description' => 'CPUs Intel y AMD para tu equipo'],
            ['name' => 'Memorias RAM', 'description' => 'Módulos de memoria para mejorar el rendimiento'],
            ['name' => 'Redes', 'description' => 'Routers, switches y adaptadores de red'],
        ];

        foreach ($categoriesData as $catData) {
            Category::create([
                'name' => $catData['name'],
                'slug' => Str::slug($catData['name']),
                'description' => $catData['description'],
                'active' => true,
            ]);
        }

        // Obtener IDs
        $brandIds = Brand::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Crear 50 productos y asignarles categorías aleatorias
        for ($i = 0; $i < 50; $i++) {
            $product = Product::factory()->create([
                'brand_id' => fake()->randomElement($brandIds),
            ]);

            // Asignar 1-3 categorías aleatorias a cada producto
            $product->categories()->attach(
                fake()->randomElements($categoryIds, fake()->numberBetween(1, 3))
            );
        }

        $this->command->info('✅ Base de datos poblada correctamente:');
        $this->command->info('   - 1 usuario admin (admin@tecnooutlet.com / password)');
        $this->command->info('   - 10 usuarios normales');
        $this->command->info('   - ' . Brand::count() . ' marcas');
        $this->command->info('   - ' . Category::count() . ' categorías');
        $this->command->info('   - ' . Product::count() . ' productos');
    }
}
