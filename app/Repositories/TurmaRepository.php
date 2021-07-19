<?php

namespace App\Repositories;

use \App\Models\Turma;
use \App\Models\Disciplina;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TurmaRepository extends EloquentRepository{

    public function __construct(Turma $turma)
    {
        $this->model = $turma;
    }

    public function create(array $data): Model
    {
        if(Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
    
        return $this->model->create($data);
    }

    public function update(array $data): Model
    {
        if(isset($data['disciplina_id']) && Disciplina::where('id', $data['disciplina_id'])->count() == 0){
            throw new Exception('disciplina id '.$data['disciplina_id'].' not found');
        }
        
        if(!$this->model->update($data))
            throw new Exception('update error verify your data send');

        return $this->model;
    }

}