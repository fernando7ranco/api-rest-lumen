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
    return 'API REST com Lumen';
});

$router->group(['prefix' => 'cursos'], function() use($router){
    $router->get('/', 'CursoController@index');
    $router->post('/create', 'CursoController@create');

});

$router->group(['prefix' => 'alunos'], function() use($router){

    $router->get('/', 'AlunoController@index');

});