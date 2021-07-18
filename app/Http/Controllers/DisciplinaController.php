<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Repositories\DisciplinaRepository;

use Exception;

class DisciplinaController extends Controller
{

    private $disciplinaRepository;
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, DisciplinaRepository $disciplinaRepository)
    {
        $this->request = $request;
        $this->disciplinaRepository = $disciplinaRepository;
        //
    }

    public function index(){
        return $this->disciplinaRepository->all();
    }

    public function show($id){

        try{
            $disciplina = $this->disciplinaRepository->find($id);
        }catch(Exception $e){
           return response()->json(['error' => $e->getMessage()], 404);
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
            'nome' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $disciplinaRepository = $this->disciplinaRepository->create($input);
            return response()->json($disciplinaRepository, 201);
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
            'nome' => 'max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try{
            $this->disciplinaRepository->find($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

        try{
            $disciplina = $this->disciplinaRepository->update($input);
            return response()->json($disciplina, 200);
        }catch(\Illuminate\Database\QueryException $ex){ 
            #dd($ex->getMessage()); 
            return response()->json(['error' => 'validate your request'], 500);
        }
    }

    public function delete($id){

        try{
            $this->disciplinaRepository->delete($id);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }
    //
}
