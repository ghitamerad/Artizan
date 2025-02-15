<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mesure>
 */
class MesureFactory extends Factory
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
            'label' => fake()->word(),
            'valeur_par_defaut' => fake()->randomFloat(2, 30, 120),
            'variable_xml' => fake()->word(),
        ];
    }
}
