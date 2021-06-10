<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Aluno;

class AlunoController extends Controller
{

    private $aluno;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Aluno $aluno)
    {
        $this->request = $request;
        $this->aluno = $aluno;
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
        return $this->aluno->all();
    }

    public function show($id){

        $aluno = $this->aluno->find($id);
        
        if(!$aluno) return response()->json(['error' => 'aluno not found'], 404);

        return response()->json($aluno);
    }

    public function create(){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = \Validator::make($input, [
            'nome' => 'required|max:255',
            'cpf' => 'required|cpf',
            'idade' => 'required|integer',
            'data_nascimento' => 'required|date',
            'matricula' => 'required|integer|min:1000|max:9999'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($this->aluno->cpfJaExiste($input['cpf'])){
            return response()->json(['error' => 'there is already a user with this cpf'], 400);
        }

        try{
            $aluno = $this->aluno->create($input);
            return response()->json($aluno, 201);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function update($id){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = \Validator::make($input, [
            'nome' => 'max:255',
            'cpf' => 'cpf',
            'idade' => 'integer',
            'data_nascimento' => 'date',
            'matricula' => 'integer|min:1000|max:9999'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $aluno = $this->aluno->find($id);

        if(!$aluno) return response()->json(['error' => 'aluno not found'], 404);

        if(isset($input['cpf']) and $aluno->cpf != $input['cpf']){
            if($this->aluno->cpfJaExiste($input['cpf'])){
                return response()->json(['error' => 'there is already a user with this cpf'], 400);
            }
        }
       
        try{
            if($aluno->update($input))
                return response()->json($aluno, 200);
            
            return response()->json(['error' => 'validate your request'], 500);

        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){
        
        $aluno = $this->aluno->find($id);

        if(!$aluno)
            return response()->json(['error' => 'aluno not found'], 404);
        
        $aluno->delete();
    }
    //
}
