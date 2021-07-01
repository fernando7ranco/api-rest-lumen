<?php

namespace App\Http\Controllers;

use App\Repositories\AlunosCursoRepository;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlunosCursoController extends Controller
{

    private $alunosCursoRepository;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, alunosCursoRepository $alunosCursoRepository)
    {
        $this->request = $request;
        $this->alunosCursoRepository = $alunosCursoRepository;
        //
    }

    public function index(){
        return $this->alunosCursoRepository->all();
    }

    public function alunosDoCurso($cursoId){

        try{
            $alunosCurso = $this->alunosCursoRepository->alunosDoCurso($cursoId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json($alunosCurso);
    }

    public function cursosDoAluno($alunoId){

        try{
            $alunosCurso = $this->alunosCursoRepository->cursosDoAluno($alunoId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json($alunosCurso);
    }    

    public function inseriAlunoNoCurso($cursoId){

        try{
            $input = getRequestJSON($this->request);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
        
        $input['curso_id'] = $cursoId;

        $validator = Validator::make($input, [
            'aluno_id' => 'required|integer'          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
  
        try{
            $alunosCurso = $this->alunosCursoRepository->inseriAlunoNoCurso($input);
            return response()->json($alunosCurso, 201);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function removerAlunoDoCurso($cursoId, $alunoId){

        try{
            $alunosCurso = $this->alunosCursoRepository->removerAlunoDoCurso($cursoId, $alunoId);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }

    }
}
