<?php

namespace App\Repositories;

use \App\Models\AlunosTurma;
use \App\Models\Aluno;
use \App\Models\Turma;

use Exception;
use Illuminate\Database\Eloquent\Collection;

class AlunosTurmaRepository{

    private $aluno;
    private $turma;
    private $alunosTurma;

    public function __construct(AlunosTurma $alunosTurma, Aluno $aluno, Turma $turma)
    {
        $this->alunosTurma = $alunosTurma;
        $this->aluno = $aluno;
        $this->turma = $turma;
    }

    public function alunosDaTurma(int $turmaId): Collection
    {
       $alunosTurma = $this->alunosTurma->where('turma_id', $turmaId)->get();

       if(!$alunosTurma->count()) throw new Exception('alunos not found');

       return $alunosTurma;
    }

    public function turmasDoAluno(int $alunoId): Collection
    {
        $alunosTurma = $this->alunosTurma->where('aluno_id', $alunoId)->get();

        if(!$alunosTurma->count()) throw new Exception('aluno not found');

        return $alunosTurma;
    }

    public function inseriAlunoNaTurma(Array $data): AlunosTurma
    {
        if(Aluno::where('id', $data['aluno_id'])->count() == 0){
            throw new Exception('aluno id '.$data['aluno_id'].' not found');
        }

        if(Turma::where('id', $data['turma_id'])->count() == 0){
           throw new Exception('turma id '.$data['turma_id'].' not found');
        }

        if($this->alunosTurma->where(['turma_id' => $data['turma_id'], 'aluno_id' => $data['aluno_id']])->count() > 0){
           throw new Exception('aluno id '.$data['aluno_id'] .' has turma id '.$data['turma_id']);
        }
  
        return $this->alunosTurma->create($data);
    }

    public function removerAlunoDaTurma(int $turmaId, int $alunoId): bool
    {
        $alunosTurma = $this->alunosTurma->where( ['turma_id' => $turmaId, 'aluno_id' => $alunoId] )->first();

        if(!$alunosTurma) throw new Exception('aluno id '.$alunoId .' not has turma id '.$turmaId);
        
        return $alunosTurma->delete();
    }
}