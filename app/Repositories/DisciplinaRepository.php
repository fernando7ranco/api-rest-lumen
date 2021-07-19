<?php

namespace App\Repositories;

use App\Models\Disciplina;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DisciplinaRepository extends EloquentRepository{

    public function __construct(Disciplina $disciplina)
    {
        $this->model = $disciplina;
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data): Model
    {
        if(!$this->model->update($data))
            throw new Exception('update error verify your data send');

        return $this->model;
    }

}