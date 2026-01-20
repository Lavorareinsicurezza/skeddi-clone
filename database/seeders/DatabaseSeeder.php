<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed companies first
        $this->call(CompanySeeder::class);

        // Create test user with company_id
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'ismail@devop360.com',
            'password' => Hash::make('devop360'),
            'role' => 'superadmin',
            'company_id' => 1,
        ]);

        // Seed permissions & roles
        $this->call(PermissionSeeder::class);
    }
}
