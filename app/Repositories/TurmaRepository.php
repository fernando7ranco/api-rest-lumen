<?php

namespace App\Repositories;

use \App\Models\Turma;
use \App\Models\Disciplina;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TurmaRepository implements EloquentRepositoryInterface{

    private $turma;

    public function __construct(Turma $turma){
        $this->turma = $turma;
    }

    public function find(int $id): Model
    {
        $this->turma = $this->turma->find($id);

        if(!$this->turma) throw new Exception('Not found Turma');

        return $this->turma;
    }

    public function all(): Collection
    {
        return $this->turma->all();
    }

    public function create(array $data): Model
    {
        if(Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
    
        return $this->turma->create($data);
    }

    public function update(array $data): Model
    {
        $aluno = $this->turma;

        if(Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
       
        if(!$aluno->update($data))
            throw new Exception('update error verify your data send');

        return $aluno;
    }

    public function delete(int $id): bool
    {
        $this->find($id);
        return $this->turma->delete();
    }

}