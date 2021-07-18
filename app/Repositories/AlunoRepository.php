<?php

namespace App\Repositories;

use App\Models\Aluno;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AlunoRepository implements EloquentRepositoryInterface{

    private $aluno;

    public function __construct(Aluno $aluno)
    {
        $this->aluno = $aluno;
    }

    public function find(int $id): Model
    {
        $this->aluno = $this->aluno->find($id);

        if(!$this->aluno) throw new Exception('Not found aluno');

        return $this->aluno;
    }

    public function all(): Collection
    { 
        return $this->aluno->all();
    }

    public function create(array $data): Model
    {
        if($this->aluno->cpfJaExiste($data['cpf'])){
           throw new Exception('there is already a user with this cpf');
        }

        return $this->aluno->create($data);
    }

    public function update(array $data): Model
    {
        $aluno = $this->aluno;

        if(isset($data['cpf']) and $aluno->cpf != $data['cpf']){
            if($this->aluno->cpfJaExiste($data['cpf'])){
                throw new Exception('there is already a user with this cpf');
            }
        }
       
        if(!$aluno->update($data))
            throw new Exception('update erro verify your data send');

        return $aluno;
    }

    public function delete(int $id): bool
    {
        $this->find($id);
        return $this->aluno->delete();
    }

}