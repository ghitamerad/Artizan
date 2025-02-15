<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\detail_commande>
 */
class DetailCommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modele_id' => \App\Models\Modele::factory(),
            'commande_id' => \App\Models\Commande::factory(),
            'quantite' => fake()->numberBetween(1, 10),
            'prix_unitaire' => fake()->randomFloat(2, 10, 500),
            'fichier_patron' => fake()->optional()->url(),
        ];
    }
}
