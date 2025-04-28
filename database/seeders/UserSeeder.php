<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'role_id' => 1,
                'name' => 'Admin User',
                'phone' => '081234567890',
                'email' => 'admin@gmail.com',
                'address' => 'Jl. Siliwangi no. 1',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'name' => 'User 1',
                'phone' => '081234567890',
                'email' => 'admin1@gmail.com',
                'address' => 'Jl. Prabu no. 1',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'name' => 'User 2',
                'phone' => '081234567890',
                'email' => 'admin2@gmail.com',
                'address' => 'Jl. Prabu no. 2',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'name' => 'User 3',
                'phone' => '081234567890',
                'email' => 'admin3@gmail.com',
                'address' => 'Jl. Prabu no. 3',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
