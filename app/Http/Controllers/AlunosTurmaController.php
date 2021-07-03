<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Repositories\AlunosTurmaRepository;

use Exception;

class AlunosTurmaController extends Controller
{

    private $request;
    private $alunosTurmaRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AlunosTurmaRepository $alunosTurmaRepository)
    {
        $this->request = $request;
        $this->alunosTurmaRepository = $alunosTurmaRepository;
        //
    }

    public function index(){
        return $this->alunosTurma->all();
    }

    public function alunosDaTurma($turmaId){

        try{
            $alunosTurma = $this->alunosTurmaRepository->alunosDaTurma($turmaId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    
        return response()->json($alunosTurma);
    }

    public function turmasDoAluno($alunoId){

        try{
            $alunosTurma = $this->alunosTurmaRepository->turmasDoAluno($alunoId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    
        return response()->json($alunosTurma);
    }    

    public function inseriAlunoNaTurma($turmaId){

        try{
            $input = getRequestJSON($this->request);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $input['turma_id'] = $turmaId;

        $validator = Validator::make($input, [
            'aluno_id' => 'required|integer'          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        try{
            $alunosTurma = $this->alunosTurmaRepository->inseriAlunoNaTurma($input);
            return response()->json($alunosTurma, 201);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function removerAlunoDaTurma($turmaId, $alunoId){
        try{
            $this->alunosTurmaRepository->removerAlunoDaTurma($turmaId, $alunoId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }
}
