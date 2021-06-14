<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Disciplina;

class DisciplinaController extends Controller
{

    private $disciplina;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Disciplina $disciplina)
    {
        $this->request = $request;
        $this->disciplina = $disciplina;
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
        return $this->disciplina->all();
    }

    public function show($id){

        $disciplina = $this->disciplina->find($id);
        
        if(!$disciplina) return response()->json(['error' => 'Disciplina not found'], 404);

        return response()->json($disciplina);
    }

    public function create(){

        try{
            $input = $this->inputJson();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = \Validator::make($input, [
            'nome' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $disciplina = $this->disciplina->create($input);
            return response()->json($disciplina, 201);
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
            'nome' => 'max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $disciplina = $this->disciplina->find($id);

        if(!$disciplina) return response()->json(['error' => 'Disciplina not found'], 404);
       
        try{
            if($disciplina->update($input))
                return response()->json($disciplina, 200);
            
            return response()->json(['error' => 'validate your request'], 500);

        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){
        
        $disciplina = $this->disciplina->find($id);

        if(!$disciplina)
            return response()->json(['error' => 'Disciplina not found'], 404);
        
        $disciplina->delete();
    }
    //
}
