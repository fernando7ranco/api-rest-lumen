<?php

namespace App\Repositories;

use App\Models\Cursos\Curso;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CursoRepository extends EloquentRepository{

    public function __construct(Curso $curso)
    {
        $this->model = $curso;
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data): Model
    {
        if(!$this->model->update($data))
            throw new Exception('update erro verify your data send');

        return $this->model;
    }
}