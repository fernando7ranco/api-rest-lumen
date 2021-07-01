<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Repositories\TurmaRepository;

use Exception;

class TurmaController extends Controller
{

    private $turmaRepository;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, TurmaRepository $turmaRepository)
    {
        $this->request = $request;
        $this->turmaRepository = $turmaRepository;
        //
    }

    public function index(){
        return $this->turmaRepository->all();
    }

    public function show($id){

        try{
            $disciplina = $this->turmaRepository->find($id);
        }catch(Exception $e){
            response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json($disciplina);
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
            'periodo_inicio' => 'required|date',
            'periodo_terminio' => 'required|date',
            'disciplina_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $turmaRepository = $this->turmaRepository->create($input);
            return response()->json($turmaRepository, 201);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
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
            'periodo_inicio' => 'date',
            'periodo_terminio' => 'date',
            'disciplina_id' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $this->turmaRepository->find($id);
        }catch(Exception $e){
            response()->json(['error' => $e->getMessage()], 404);
        }

        try{
            $disciplina = $this->turmaRepository->update($input);
            return response()->json($disciplina, 200);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){

        try{
            $this->turmaRepository->delete($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }
    //
}
