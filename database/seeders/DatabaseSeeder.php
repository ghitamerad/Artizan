<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Modele;
use App\Models\Attribut;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Categorie::factory(5)->create()->each(function ($categorie) {
            $modeles = Modele::factory(3)->create([
                'categorie_id' => $categorie->id
            ]);

            // Pour chaque modèle, attache des attributs
            $modeles->each(function ($modele) {
                // Crée 2 à 4 attributs pour chaque modèle
                $attributs = Attribut::factory(rand(1, 3))->create();
                $modele->attributs()->attach($attributs->pluck('id'));
            });
        });

        // Appel des autres seeders
        $this->call(UserSeeder::class);

    }
}
