<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Repositories\CursoRepository;

class CursoController extends Controller
{

    private $cursoRespository;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, CursoRepository $cursoRespository)
    {
        $this->request = $request;
        $this->cursoRespository = $cursoRespository;
        //
    }

    public function index(){
        return $this->cursoRespository->all();
    }

    public function show($id){

        try{
            $curso = $this->cursoRespository->find($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json($curso);
    }

    public function create(){

        try{
            $input = getRequestJSON($this->request);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = Validator::make($input, [
            'nome' => 'required|max:255',
            'descricao' => 'required|max:255',
            'conteudo' => 'required|max:255',
            'valor' => 'required|numeric|between:0,9999.99'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $curso = $this->cursoRespository->create($input);
            return response()->json($curso, 201);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function update($id){

        try{
            $input = getRequestJSON($this->request);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = Validator::make($input, [
            'nome' => 'max:255',
            'descricao' => 'max:255',
            'conteudo' => 'max:255',
            'valor' => 'numeric|between:0,9999.99'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $this->cursoRespository->find($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

        try{
            $curso = $this->cursoRespository->update($input);
            return response()->json($curso, 200);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){

        try{
            $this->cursoRespository->delete($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }

    }
    //
}
