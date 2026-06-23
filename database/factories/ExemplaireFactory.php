<?php

namespace Database\Factories;

use App\Models\Exemplaire;
use App\Models\Livre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exemplaire>
 */
class ExemplaireFactory extends Factory
{
    protected $model = Exemplaire::class;

    public function definition(): array
    {
        return [
            'livre_id' => Livre::factory(),
            'code'     => fake()->unique()->bothify('EX-#####'),
            'statut'   => 'Disponible',
        ];
    }
}
