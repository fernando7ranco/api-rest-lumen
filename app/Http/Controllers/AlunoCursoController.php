<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\AlunosCurso;
use \App\Models\Aluno;
use \App\Models\Cursos\Curso;
use Illuminate\Support\Facades\Validator;



class AlunoCursoController extends Controller
{

    private $alunosCurso;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AlunosCurso $alunosCurso)
    {
        $this->request = $request;
        $this->alunosCurso = $alunosCurso;
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
        return $this->alunosCurso->all();
    }

    public function alunosDoCurso($cursoId){

        $alunosCurso = $this->alunosCurso->where('curso_id', $cursoId)->get();
        
        if(!$alunosCurso) return response()->json(['error' => 'curso not found'], 404);

        return response()->json($alunosCurso);
    }

    public function cursosDoAluno($alunoId){

        $alunosCurso = $this->alunosCurso->where('aluno_id', $alunoId)->get();
        
        if(!$alunosCurso) return response()->json(['error' => 'aluno not found'], 404);

        return response()->json($alunosCurso);
    }    

    public function inseriAlunoNoCurso($cursoId){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
        
        $input['curso_id'] = $cursoId;

        $validator = \Validator::make($input, [
            'aluno_id' => 'required|integer'          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(Aluno::where('id', $input['aluno_id'])->count() == 0){
            return response()->json(['error' => 'aluno id '.$input['aluno_id'].' not found'], 400);
        }

        if(Curso::where('id', $input['curso_id'])->count() == 0){
            return response()->json(['error' => 'curso id '.$input['curso_id'].' not found'], 400);
        }

        if($this->alunosCurso->where(['curso_id' => $input['curso_id'], 'aluno_id' => $input['aluno_id']])->count() > 0){
            return response()->json(['error' => 'aluno id '.$input['aluno_id'] .' has curso id '.$input['curso_id']], 400);
        }
  
        try{
            $alunosCurso = $this->alunosCurso->create($input);
            return response()->json($alunosCurso, 201);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }


    public function removerAlunoDoCurso($cursoId, $alunoId){

        $alunosCurso = $this->alunosCurso->where( ['curso_id' => $cursoId, 'aluno_id' => $alunoId] )->first();

        if(!$alunosCurso->count())
            return response()->json(['error' => 'aluno id '.$cursoId .' not has curso id '.$alunoId], 400);
        
        $alunosCurso->delete();
    }
}
