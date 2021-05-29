<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;

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
        //
    }

    public function index(){
        return $this->curso->all();
    }

    //
}
