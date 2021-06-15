<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\AlunosTurma;
use \App\Models\Aluno;
use \App\Models\Turma;


class AlunoTurmaController extends Controller
{

    private $alunosTurma;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AlunosTurma $alunosTurma)
    {
        $this->request = $request;
        $this->alunosTurma = $alunosTurma;
        //
    }

    private function inputJson(){

        if(!$this->request->isJson()) throw new \Exception('Invalid JSON recieved, your Content-type is not aplication/json');
            #abort(400, 'Invalid JSON recieved, your content-type is not aplication/json');
      
        $input = $this->request->json()->all();

        if(!$input) throw new \Exception('content in body is not JSON');
            #abort(400,'content is not JSON');
            
        return $input;
    }

    public function index(){
        return $this->alunosTurma->all();
    }

    public function alunosDaTurma($turmaId){

        $alunosTurma = $this->alunosTurma->where('turma_id', $turmaId)->get();
        
        if(!$alunosTurma) return response()->json(['error' => 'curso not found'], 404);

        return response()->json($alunosTurma);
    }

    public function turmasDoAluno($alunoId){

        $alunosTurma = $this->alunosTurma->where('aluno_id', $alunoId)->get();
        
        if(!$alunosTurma) return response()->json(['error' => 'aluno not found'], 404);

        return response()->json($alunosTurma);
    }    

    public function inseriAlunoNaTurma($turmaId){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
        
        $input['turma_id'] = $turmaId;

        $validator = \Validator::make($input, [
            'aluno_id' => 'required|integer'          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(Aluno::where('id', $input['aluno_id'])->count() == 0){
            return response()->json(['error' => 'aluno id '.$input['aluno_id'].' not found'], 400);
        }

        if(Turma::where('id', $input['turma_id'])->count() == 0){
            return response()->json(['error' => 'turma id '.$input['turma_id'].' not found'], 400);
        }

        if($this->alunosTurma->where(['turma_id' => $input['turma_id'], 'aluno_id' => $input['aluno_id']])->count() > 0){
            return response()->json(['error' => 'aluno id '.$input['aluno_id'] .' has turma id '.$input['turma_id']], 400);
        }
  
        try{
            $alunosTurma = $this->alunosTurma->create($input);
            return response()->json($alunosTurma, 201);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }


    public function removerAlunoDaTurma($turmaId, $alunoId){

        $alunosTurma = $this->alunosTurma->where( ['turma_id' => $turmaId, 'aluno_id' => $alunoId] )->first();

        if(!$alunosTurma->count())
            return response()->json(['error' => 'aluno id '.$cursoId .' not has turma id '.$turmaId], 400);
        
        $alunosTurma->delete();
    }
}
