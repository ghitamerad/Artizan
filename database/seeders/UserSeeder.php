<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $password = Hash::make('12345678');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Responsable',
            'email' => 'responsable@gmail.com',
            'role' => 'gerante',
            'password' => $password,
        ]);

        User::create([
            'name' => 'couturière 1',
            'email' => 'couturiere1@gmail.com',
            'role' => 'couturiere',
            'password' => $password,
        ]);

        User::create([
            'name' => 'couturière 2',
            'email' => 'couturiere2@gmail.com',
            'role' => 'couturiere',
            'password' => $password,
        ]);
    }
}
