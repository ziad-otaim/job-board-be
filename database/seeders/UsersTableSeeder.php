<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    
        \App\Models\User::factory()->create([
            'name' => 'Employer User',
            'email' => 'employer@example.com',
            'role' => 'employer',
        ]);
    
        \App\Models\User::factory()->create([
            'name' => 'Candidate User',
            'email' => 'candidate@example.com',
            'role' => 'candidate',
        ]);
    }
}
