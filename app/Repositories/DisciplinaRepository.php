<?php

namespace App\Repositories;

use App\Models\Disciplina;

use Exception;

class DisciplinaRepository{

    private $disciplina;

    public function __construct(Disciplina $disciplina){
        $this->disciplina = $disciplina;
    }

    public function find(int $id): Disciplina{

        $this->disciplina = $this->disciplina->find($id);

        if(!$this->disciplina) throw new Exception('Not found disciplina');

        return $this->disciplina;
    }

    public function all(){
        return $this->disciplina->all();
    }

    public function create(Array $data): Disciplina{
        return $this->disciplina->create($data);
    }

    public function update(Array $data): disciplina{

        $aluno = $this->disciplina;
       
        if(!$aluno->update($data))
            throw new Exception('update error verify your data send');

        return $aluno;
            
    }

    public function delete(Int $id){
        $this->find($id);
        $this->disciplina->delete();
    }

}