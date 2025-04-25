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
            'name' => 'Paco',
            'email' => 'paco@gmail.com',
            'role' => 'admin',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Coco',
            'email' => 'coco@gmail.com',
            'role' => 'gerante',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Selma',
            'email' => 'selma@gmail.com',
            'role' => 'couturiere',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Ghita',
            'email' => 'ghita@gmail.com',
            'role' => 'client',
            'password' => $password,
        ]);
    }
}
