<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate([
            'email' => 'admin@selada.local'
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
        ]);

        // Placeholder for creating sample employees
        // Employee::factory()->count(5)->create();
    }
}
