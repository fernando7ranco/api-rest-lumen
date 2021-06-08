<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Cursos\Curso;

class CursoController extends Controller
{

    private $curso;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Curso $curso)
    {
        $this->request = $request;
        $this->curso = $curso;
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
        return $this->curso->all();
    }

    public function show($id){

        $curso = $this->curso->find($id);
        
        if(!$curso) return response()->json(['error' => 'curso not found'], 404);

        return response()->json($curso);
    }

    public function create(){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = \Validator::make($input, [
            'nome' => 'required|max:255',
            'descricao' => 'required|max:255',
            'conteudo' => 'required|max:255',
            'valor' => 'required|numeric|between:0,99.99'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $curso = $this->curso->create($input);
            return response()->json($curso, 201);
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
            'descricao' => 'max:255',
            'conteudo' => 'max:255',
            'valor' => 'numeric|between:0,99.99'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $curso = $this->curso->find($id);

        if(!$curso) return response()->json(['error' => 'curso not found'], 404);

        try{
            if($curso->update($input))
                return response()->json($curso, 200);
            
            return response()->json(['error' => 'validate your request'], 500);

        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){
        
        $curso = $this->curso->find($id);

        if(!$curso)
            return response()->json(['error' => 'curso not found'], 404);
        
        $curso->delete();
    }
    //
}
