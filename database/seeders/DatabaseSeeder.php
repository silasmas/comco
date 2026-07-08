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
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => User::factory()->make()->password,
                'is_super_admin' => true,
            ]
        );

        $this->call(InstitutionSeeder::class);
        $this->call(InstitutionSettingsSeeder::class);
        $this->call(NavigationSeeder::class);
        $this->call(HomeContentSeeder::class);
        $this->call(ContactContentSeeder::class);
        $this->call(PageAttachmentsSeeder::class);
        $this->call(EServiceDefinitionSeeder::class);
        $this->call(PostsSeeder::class);
    }
}
