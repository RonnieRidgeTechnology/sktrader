<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FaqSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SeoSettingSeeder::class,
            HomepageSectionSeeder::class,
            VideoReelSeeder::class,
            PageContentSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@zuaazgear.com',
            'is_admin' => true,
        ]);
    }
}
