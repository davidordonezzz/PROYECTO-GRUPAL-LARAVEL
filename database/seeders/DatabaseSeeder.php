<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'administrador@example.com',
            'password' => 'password',
            'is_admin' => true
        ]);

        User::create([
            'name' => 'hug0gil',
            'email' => 'hugogilb2005@gmail.com',
            'password' => 'password',
            'is_admin' => false
        ]);

        // Generar usuarios de prueba adicionales
        User::factory()->count(5)->create();

        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
