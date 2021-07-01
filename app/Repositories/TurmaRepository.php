<?php

namespace App\Repositories;

use \App\Models\Turma;
use \App\Models\Disciplina;

use Exception;

class TurmaRepository{

    private $turma;

    public function __construct(Turma $turma){
        $this->turma = $turma;
    }

    public function find(int $id): Turma{

        $this->turma = $this->turma->find($id);

        if(!$this->turma) throw new Exception('Not found Turma');

        return $this->turma;
    }

    public function all(){
        return $this->turma->all();
    }

    public function create(Array $data): Turma{

        if(Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
    
        return $this->turma->create($data);
    }

    public function update(Array $data): Turma{

        $aluno = $this->turma;

        if(Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
       
        if(!$aluno->update($data))
            throw new Exception('update error verify your data send');

        return $aluno;
            
    }

    public function delete(Int $id){
        $this->find($id);
        $this->turma->delete();
    }

}