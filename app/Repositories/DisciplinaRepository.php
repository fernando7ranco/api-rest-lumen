<?php

namespace App\Repositories;

use App\Models\Disciplina;

use Exception;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DisciplinaRepository implements EloquentRepositoryInterface{

    private $disciplina;

    public function __construct(Disciplina $disciplina){
        $this->disciplina = $disciplina;
    }

    public function find(int $id): Model
    {
        $this->disciplina = $this->disciplina->find($id);

        if(!$this->disciplina) throw new Exception('Not found disciplina');

        return $this->disciplina;
    }

    public function all(): Collection
    {
        return $this->disciplina->all();
    }

    public function create(array $data): Model
    {
        return $this->disciplina->create($data);
    }

    public function update(array $data): Model
    {
        $aluno = $this->disciplina;
       
        if(!$aluno->update($data))
            throw new Exception('update error verify your data send');

        return $aluno;
    }

    public function delete(int $id): bool
    {
        $this->find($id);
        return $this->disciplina->delete();
    }

}