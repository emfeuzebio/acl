<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Emerson Euzébio',
            'email' => 'emfeuzebio@hotmail.com',
            'password' => '$2y$12$h6VXebJquAIqW7emOThnMuw9n8vv61acD3nyPmoHc4nq6E9S1ceiy',
            'created_at' => '2024-08-11 00:00:00',
            'updated_at' => '2024-08-11 00:00:00',
        ]);

        User::create([
            'name' => 'Sandra Euzébio',
            'email' => 'sandra@hotmail.com',
            'password' => '$2y$12$h6VXebJquAIqW7emOThnMuw9n8vv61acD3nyPmoHc4nq6E9S1ceiy',
            'created_at' => '2024-08-11 00:00:00',
            'updated_at' => '2024-08-11 00:00:00',
        ]);

        User::create([
            'name' => 'Douglas Euzébio',
            'email' => 'douglas@hotmail.com',
            'password' => '$2y$12$h6VXebJquAIqW7emOThnMuw9n8vv61acD3nyPmoHc4nq6E9S1ceiy',
            'created_at' => '2024-08-11 00:00:00',
            'updated_at' => '2024-08-11 00:00:00',
        ]);

        User::create([
            'name' => 'Allan Euzébio',
            'email' => 'allan@hotmail.com',
            'password' => '$2y$12$h6VXebJquAIqW7emOThnMuw9n8vv61acD3nyPmoHc4nq6E9S1ceiy',
            'created_at' => '2024-08-11 00:00:00',
            'updated_at' => '2024-08-11 00:00:00',
        ]);

        User::create([
            'name' => 'Laís Euzébio',
            'email' => 'lais@hotmail.com',
            'password' => '$2y$12$h6VXebJquAIqW7emOThnMuw9n8vv61acD3nyPmoHc4nq6E9S1ceiy',
            'created_at' => '2024-08-11 00:00:00',
            'updated_at' => '2024-08-11 00:00:00',
        ]);

        $this->call([
            ACL_EntidadesSeeder::class,
            ACL_PerfilsSeeder::class,
            ACL_PermissaosSeeder::class,
            ACL_EntidadePerfilSeeder::class,
            ACL_PerfilUserSeeder::class,
        ]);
    }
}