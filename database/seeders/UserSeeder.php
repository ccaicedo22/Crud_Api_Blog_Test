<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Usuario Admin',
            'email' => 'usuario.admin@gmail.com',
            'password' => Hash::make('Colombia2025*'),
        ]);
    }
}
