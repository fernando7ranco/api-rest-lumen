<?php

namespace App\Repositories;

use App\Models\Aluno;

use Exception;

class AlunoRepository{

    private $aluno;

    public function __construct(Aluno $aluno){
        $this->aluno = $aluno;
    }

    public function find(int $id): Aluno{

        $this->aluno = $this->aluno->find($id);

        if(!$this->aluno) throw new Exception('Not found aluno');

        return $this->aluno;
    }

    public function all(){
        return $this->aluno->all();
    }

    public function create(Array $data): Aluno{

        if($this->aluno->cpfJaExiste($data['cpf'])){
           throw new Exception('there is already a user with this cpf');
        }

        return $this->aluno->create($data);
    }

    public function update(Array $data): Aluno{

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

    public function delete(Int $id){
        $this->find($id);
        $this->aluno->delete();
    }

}