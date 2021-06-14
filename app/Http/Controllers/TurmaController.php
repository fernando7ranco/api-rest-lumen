<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Turma;

use \App\Models\Disciplina;

class TurmaController extends Controller
{

    private $turma;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Turma $turma)
    {
        $this->request = $request;
        $this->turma = $turma;
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
        return $this->turma->all();
    }

    public function show($id){

        $turma = $this->turma->find($id);
        
        if(!$turma) return response()->json(['error' => 'turma not found'], 404);

        return response()->json($turma);
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
            'periodo_inicio' => 'required|date',
            'periodo_terminio' => 'required|date',
            'disciplina_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(Disciplina::where('id', $input['disciplina_id'])->count() == 0){
            return response()->json(['error' => 'disciplina id '.$input['disciplina_id'].' not found'], 400);
        }
        
        try{
            $turma = $this->turma->create($input);
            return response()->json($turma, 201);
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
            'periodo_inicio' => 'date',
            'periodo_terminio' => 'date',
            'disciplina_id' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(Disciplina::where('id', $input['disciplina_id'])->count() == 0){
            return response()->json(['error' => 'disciplina id '.$input['disciplina_id'].' not found'], 400);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $turma = $this->turma->find($id);

        if(!$turma) return response()->json(['error' => 'turma not found'], 404);

        try{
            if($turma->update($input))
                return response()->json($turma, 200);
            
            return response()->json(['error' => 'validate your request'], 500);

        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){
        
        $turma = $this->turma->find($id);

        if(!$turma)
            return response()->json(['error' => 'turma not found'], 404);
        
        $turma->delete();
    }
    //
}
