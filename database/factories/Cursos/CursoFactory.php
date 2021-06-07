<?php

namespace Database\Factories\Cursos;

use App\Models\Cursos\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Curso::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->sentence,
            'descricao' => $this->faker->sentence,
            'conteudo' => $this->faker->paragraph,
            'valor' => $this->faker->randomFloat(2,0,10)
        ];
    }
}
