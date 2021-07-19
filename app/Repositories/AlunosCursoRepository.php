<?php

namespace App\Repositories;

use App\Models\AlunosCurso;
use App\Models\Aluno;
use App\Models\Cursos\Curso;

use Exception;
use Illuminate\Database\Eloquent\Collection;

class AlunosCursoRepository{

    private $aluno;
    private $curso;
    private $alunosCurso;

    public function __construct(Aluno $aluno, Curso $curso, AlunosCurso $alunosCurso){
        $this->aluno = $aluno;
        $this->curso = $curso;
        $this->alunosCurso = $alunosCurso;
    }

    public function alunosDoCurso(int $cursoId): Collection
    {
        $alunosCurso = $this->alunosCurso->where('curso_id', $cursoId)->get();

        if(!$alunosCurso->count()) throw new Exception('Not found alunos curso id '. $cursoId);

        return $alunosCurso;
    }

    public function all(): Collection
    {
        return $this->alunosCurso->all();
    }

    public function cursosDoAluno(int $alunoId): Collection
    {
        $alunosCurso = $this->alunosCurso->where('aluno_id', $alunoId)->get();
        
        if(!$alunosCurso->count()) throw new Exception('aluno not found');

        return $alunosCurso;
    } 

    public function inseriAlunoNoCurso(array $data): AlunosCurso
    {
        if(Aluno::where('id', $data['aluno_id'])->count() == 0){
            throw new Exception('aluno id '. $data['aluno_id'] .' not found');
        }

        if(Curso::where('id', $data['curso_id'])->count() == 0){
            throw new Exception('curso id '.$data['curso_id'].' not found');
        }

        if($this->alunosCurso->where(['curso_id' => $data['curso_id'], 'aluno_id' => $data['aluno_id']])->count() > 0){
            throw new Exception('aluno id '.$data['aluno_id'] .' has curso id '.$data['curso_id']);
        }
  
        return $this->alunosCurso->create($data);
    }

    public function removerAlunoDoCurso(int $cursoId, int $alunoId): bool
    {
        $alunosCurso = $this->alunosCurso->where( ['curso_id' => $cursoId, 'aluno_id' => $alunoId] )->first();

        if(!$alunosCurso)
            throw new Exception('aluno id '.$cursoId .' not has curso id '.$alunoId);
        
        return $alunosCurso->delete();
    }

}