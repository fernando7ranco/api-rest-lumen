<?php

namespace App\Repositories;

use App\Models\Cursos\Curso;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CursoRepository implements EloquentRepositoryInterface{

    private $curso;

    public function __construct(Curso $curso)
    {
        $this->curso = $curso;
    }

    public function find(int $id): Model
    {
        $this->curso = $this->curso->find($id);

        if(!$this->curso) throw new Exception('Not found curso');

        return $this->curso;
    }

    public function all(): Collection
    {
        return $this->curso->all();
    }

    public function create(array $data): Model
    {
        return $this->curso->create($data);
    }

    public function update(array $data): Model
    {
        $aluno = $this->curso;
       
        if(!$aluno->update($data))
            throw new Exception('update erro verify your data send');

        return $aluno;
            
    }

    public function delete(int $id): bool
    {
        $this->find($id);
        return $this->curso->delete();
    }

}