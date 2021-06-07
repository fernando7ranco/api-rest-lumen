<?php

namespace Database\Factories;

use App\Models\Aluno;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlunoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Aluno::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('pt_BR');
        return [
            'nome' => $faker->name,
            'cpf' => $faker->cpf,
            'idade' => rand(10,50),
            'data_nascimento' => $faker->date,
            'matricula' => rand(1000,9999)
        ];
    }
}
