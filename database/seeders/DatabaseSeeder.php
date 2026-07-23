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
        $this->call(SuperAdminSeeder::class);
        $this->call(CProgrammingTestSeeder::class);
        $this->call(ProgrammingCourseExpansionSeeder::class);
        $this->call(LmsQuizSeeder::class);

        User::factory()->firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
        ]);
    }
}
