<?php

namespace App\Repositories;

use App\Models\Aluno;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AlunoRepository extends EloquentRepository{

    public function __construct(Aluno $aluno)
    {
        $this->model = $aluno;
    }

    public function create(array $data): Model
    {
        if($this->model->cpfJaExiste($data['cpf'])){
           throw new Exception('there is already a user with this cpf');
        }

        return $this->model->create($data);
    }

    public function update(array $data): Model
    {
        $aluno = $this->model;

        if(isset($data['cpf']) and $aluno->cpf != $data['cpf']){
            if($this->model->cpfJaExiste($data['cpf'])){
                throw new Exception('there is already a user with this cpf');
            }
        }
       
        if(!$aluno->update($data))
            throw new Exception('update erro verify your data send');

        return $aluno;
    }

}