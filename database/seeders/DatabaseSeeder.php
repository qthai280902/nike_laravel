<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Customer Test
        User::factory()->create([
            'display_id' => '#000001',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
            'role' => 'customer',
        ]);

        // Tạo tài khoản Admin Test
        User::factory()->create([
            'display_id' => '#000000',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'display_id' => '#777777',
            'name' => 'ADThai',
            'email' => 'thai@gmail.com ',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // Gọi các seeder khác
        $this->call([
            CategorySeeder::class,
            LiveProductSeeder::class,
            LandingArticleSeeder::class,
        ]);
    }
}
