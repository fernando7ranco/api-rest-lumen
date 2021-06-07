<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Cursos\Curso;

class CursoController extends Controller
{

    private $curso;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->curso = new Curso;
        $this->request = new Request();
        //
    }

    public function index(){
        return $this->curso->all();
    }

    public function create(){
        
        $input = $this->request->json()->all();

        if(!$input) return response()->json(['error' => 'content is not JSON'], 400);

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


    //
}
