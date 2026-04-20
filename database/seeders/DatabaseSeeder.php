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
        $now = now();

        User::query()->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@smartschool.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => $now,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Principal User',
                'email' => 'principal@smartschool.test',
                'password' => Hash::make('password'),
                'role' => 'principal',
                'email_verified_at' => $now,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Faculty User',
                'email' => 'faculty@smartschool.test',
                'password' => Hash::make('password'),
                'role' => 'faculty',
                'email_verified_at' => $now,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Student User',
                'email' => 'student@smartschool.test',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => $now,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
