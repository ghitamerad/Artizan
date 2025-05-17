<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\modele>
 */
class ModeleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorie_id' => \App\Models\Categorie::factory(),
            'nom' => fake()->word(),
            'description' => fake()->optional()->paragraph(),
            'prix' => fake()->randomFloat(2, 10, 100),
            'xml' => '<xml><data>' . fake()->word() . '</data></xml>',
            'patron' => fake()->word(),
            'stock' => $this->faker->boolean(90),
            'sur_commande' => $this->faker->boolean(10),
        ];
    }
}
