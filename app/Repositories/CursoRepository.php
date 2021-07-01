<?php

namespace App\Repositories;

use App\Models\Cursos\Curso;

use Exception;

class CursoRepository{

    private $curso;

    public function __construct(Curso $curso){
        $this->curso = $curso;
    }

    public function find(int $id): Curso{

        $this->curso = $this->curso->find($id);

        if(!$this->curso) throw new Exception('Not found curso');

        return $this->curso;
    }

    public function all(){
        return $this->curso->all();
    }

    public function create(Array $data): Curso{
        return $this->curso->create($data);
    }

    public function update(Array $data): Curso{

        $aluno = $this->curso;
       
        if(!$aluno->update($data))
            throw new Exception('update erro verify your data send');

        return $aluno;
            
    }

    public function delete(Int $id){
        $this->find($id);
        $this->curso->delete();
    }

}