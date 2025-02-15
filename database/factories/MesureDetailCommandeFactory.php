<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mesure_detail_commande>
 */
class MesureDetailCommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mesure_id' => \App\Models\Mesure::factory(),
            'details_commande_id' => \App\Models\detail_commande::factory(),
            'valeur_mesure' => fake()->randomFloat(2, 30, 120),
        ];
    }
}
