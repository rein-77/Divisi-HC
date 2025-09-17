<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            // Panggil seeder Admin agar membuat akun admin default
            AdminSeeder::class,
            
            // Panggil seeder DemoData untuk membuat data contoh
            DemoDataSeeder::class,
        ]);
    }
}
