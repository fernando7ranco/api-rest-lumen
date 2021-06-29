<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Repositories\AlunoRepository;

class AlunoController extends Controller
{

    private $alunoRepository;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AlunoRepository $alunoRepository)
    {
        $this->request = $request;
        $this->alunoRepository = $alunoRepository;
        //
    }

    public function index(){
        return $this->alunoRepository->all();
    }

    public function show($id){
        try{
            $aluno = $this->alunoRepository->find($id);
            return response()->json($aluno);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMensse], 404);
        }       
    }

    public function create(){

        try{
            $input = getRequestJSON($this->request);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $validator = Validator::make($input, [
            'nome' => 'required|max:255',
            'cpf' => 'required|cpf',
            'idade' => 'required|integer',
            'data_nascimento' => 'required|date',
            'matricula' => 'required|integer|min:1000|max:9999'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $aluno = $this->alunoRepository->create($input);
            return response()->json($aluno, 201);
        }catch(\Exception $ex){ 
            return response()->json(['error' => $ex->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
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
            'cpf' => 'cpf',
            'idade' => 'integer',
            'data_nascimento' => 'date',
            'matricula' => 'integer|min:1000|max:9999'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $this->alunoRepository->find($id);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()], 404);
        }       
       
        try{
            $aluno  = $this->alunoRepository->update($input);
            return response()->json($aluno, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){
        try{
            $this->alunoRepository->delete($id);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
