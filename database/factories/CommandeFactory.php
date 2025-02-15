<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\commande>
 */
class CommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'date_commande' => fake()->dateTimeBetween('-1 year', 'now'),
     'statut' => fake()->randomElement(['en_attente', 'validee', 'expediee', 'annulee']),
            'montant_total' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
