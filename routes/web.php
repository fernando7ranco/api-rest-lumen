<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function() use($router) {
    return 'API REST com Lumen version ' . $router->app->version();
});

$router->group(['middleware' => 'auth'], function() use($router) {

    $router->group(['prefix' => 'cursos'], function() use($router){
        $router->get('/', 'CursoController@index'); #lista todos os curso
        $router->get('/show/{id:[0-9]+}', 'CursoController@show'); #mostra o curso por ID
        $router->post('/create', 'CursoController@create'); #cria um novo curso
        $router->put('/update/{id:[0-9]+}', 'CursoController@update'); #atualiza o curso por ID
        $router->delete('/delete/{id:[0-9]+}', 'CursoController@delete'); #deleta o curso por ID

        $router->group(['prefix' => 'curso/{cursoId:[0-9]+}/'], function() use($router){
            $router->get('alunos', 'AlunosCursoController@alunosDoCurso'); #mostra o alunos por curso ID
            $router->post('alunos', 'AlunosCursoController@inseriAlunoNoCurso'); #inseri aluno em curso
            $router->delete('alunos/{alunoId:[0-9]+}/','AlunosCursoController@removerAlunoDoCurso');
        });
        
    });

    $router->group(['prefix' => 'alunos'], function() use($router){
        $router->get('/', 'AlunoController@index');
        $router->get('/show/{id:[0-9]+}', 'AlunoController@show'); #mostra o aluno por ID
        $router->post('/create', 'AlunoController@create'); #cria um novo aluno
        $router->put('/update/{id:[0-9]+}', 'AlunoController@update'); #atualiza o aluno por ID
        $router->delete('/delete/{id:[0-9]+}', 'AlunoController@delete'); #deleta o aluno por ID

        $router->get('aluno/{alunoId:[0-9]+}/cursos', 'AlunosCursoController@cursosDoAluno'); #mostra o alunos por curso ID

        $router->get('aluno/{alunoId:[0-9]+}/turmas', 'AlunosTurmaController@turmasDoAluno'); #mostra o alunos por curso ID

    });

    $router->group(['prefix' => 'disciplinas'], function() use($router){
        $router->get('/', 'DisciplinaController@index');
        $router->get('/show/{id:[0-9]+}', 'DisciplinaController@show'); #mostra o disciplina por ID
        $router->post('/create', 'DisciplinaController@create'); #cria um novo disciplina
        $router->put('/update/{id:[0-9]+}', 'DisciplinaController@update'); #atualiza o disciplina por ID
        $router->delete('/delete/{id:[0-9]+}', 'DisciplinaController@delete'); #deleta o disciplina por ID
    });

    $router->group(['prefix' => 'turmas'], function() use($router){
        $router->get('/', 'TurmaController@index');
        $router->get('/show/{id:[0-9]+}', 'TurmaController@show'); #mostra o Turmas por ID
        $router->post('/create', 'TurmaController@create'); #cria um novo Turmas
        $router->put('/update/{id:[0-9]+}', 'TurmaController@update'); #atualiza o Turmas por ID
        $router->delete('/delete/{id:[0-9]+}', 'TurmaController@delete'); #deleta o disciplina por ID


        $router->group(['prefix' => 'turma/{turmaId:[0-9]+}/'], function() use($router){
            $router->get('alunos', 'AlunosTurmaController@alunosDaTurma'); #mostra o alunos por curso ID
            $router->post('alunos', 'AlunosTurmaController@inseriAlunoNaTurma'); #inseri aluno em curso
            $router->delete('alunos/{alunoId:[0-9]+}/','AlunosTurmaController@removerAlunoDaTurma');
        });
    });

});