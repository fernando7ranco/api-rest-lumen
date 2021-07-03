<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AlunosTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * teste  verifca se lista todos alunos
     *
     * @return void
     */
    public function testListarTodosAlunos()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/alunos');

        $this->get('/alunos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);
    }
    
    /**
     * teste  verifca se encotra aluno e também se não retorna aluno
     *
     * @return void
     */
    public function testMostrarAluno()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/alunos/show/1');

        $this->get('/alunos/show/1', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->get('/alunos/show/3', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
    }

    /**
     * teste  cria aluno com parametros validos e tenta criar com cpf já existente e sem parametros
     *
     * @return void
     */

    public function testCriarAluno()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', '/alunos/create');

        $dados = array(
                    'nome' => 'Fernando Soares Franco',
                    'cpf' => '48701840010',
                    'idade' => 23,
                    'data_nascimento' => '1998-03-20',
                    'matricula' => rand(1000,9999),
                );

        $this->json('POST', '/alunos/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(201, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $dados = array(
                    'nome' => 'Fernando Soares Franco',
                    'cpf' => '02386439054',#cpf existente
                    'idade' => 23,
                    'data_nascimento' => '1998-03-20',
                    'matricula' => '5555',
                );

        $this->json('POST', '/alunos/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
        
        $dados = array();
        $this->json('POST', '/alunos/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testEditarAluno()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('PUT', '/alunos/update/3');

        $dados = array(
                    'nome' => 'Fernando Soares Franco',
                    'cpf' => '48701840010',
                    'idade' => 23,
                    'data_nascimento' => '1998-03-20',
                    'matricula' => '5555',
                );
        $this->json('PUT', '/alunos/update/3', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $this->json('PUT', '/alunos/update/0', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
        
        $dados = array();
        $this->json('PUT', '/alunos/update/3', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testDeletarAluno()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/alunos/delete/3');

        $this->delete('/alunos/delete/3', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/alunos/delete/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    private function verificaRotaEstaBloqueadaParaTokenErrado($metodo, $uri){
        $tokenErrado = 'TOKEN-INVALIDO';
        $this->call($metodo, $uri, [], [], [], ['Authorization' => 'Bearer ' . $tokenErrado]);
        $this->assertEquals(401, $this->response->status());
        $this->assertEquals('Unauthorized.', $this->response->content());
    }

    private function isJsonResponse(){
        $conteudoResposta = json_decode($this->response->content(), true);
        $this->assertNotEquals(null, $conteudoResposta, 'response is not JSON');
        return $conteudoResposta;
    }
}
