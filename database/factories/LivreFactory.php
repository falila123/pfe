<?php

namespace Database\Factories;

use App\Models\Livre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livre>
 */
class LivreFactory extends Factory
{
    protected $model = Livre::class;

    public function definition(): array
    {
        return [
            'titre'                => fake()->sentence(3),
            'cote'                 => fake()->unique()->bothify('COTE-####'),
            'isbn'                 => fake()->unique()->numerify('978#########'),
            'categorie'            => 'Informatique',
            'sous_categorie_dewey' => '005',
            'type_livre'           => 'Manuel',
            'langue'               => 'Français',
            'couverture'           => null,
            'description'          => fake()->paragraph(),
        ];
    }
}
