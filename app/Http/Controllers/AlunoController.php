<?php

namespace App\Http\Controllers;

use \App\Models\Aluno;

class AlunoController extends Controller
{

    private $aluno;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->aluno = new Aluno;
        //
    }

    public function index(){
        return $this->aluno->all();
    }

    //
}
