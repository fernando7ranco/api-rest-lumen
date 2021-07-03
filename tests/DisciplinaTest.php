<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DisciplinaTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * teste  verifca se lista todos disciplinas
     *
     * @return void
     */
    public function testListarTodosDisciplinas()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/disciplinas');

        $this->get('/disciplinas', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);
    }
    
    /**
     * teste  verifca se encontra a disciplina e também se não retorna a disciplina
     *
     * @return void
     */
    public function testMostrarDisciplina()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/disciplinas/show/1');

        $this->get('/disciplinas/show/1', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->get('/disciplinas/show/3', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
    }

    /**
     * teste cria disciplina com parametros validos e tenta criar com sem parametros
     *
     * @return void
     */

    public function testCriarDisciplina()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', '/disciplinas/create');

        $dados = array(
                    'nome' => 'Teste de disciplina'
                );

        $this->json('POST', '/disciplinas/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(201, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
        
        $dados = array();
        $this->json('POST', '/disciplinas/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testEditarDisciplina()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('PUT', '/disciplinas/update/3');

        $dados = array(
                    'nome' => 'Teste de disciplina'
                );
        $this->json('PUT', '/disciplinas/update/3', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $this->json('PUT', '/disciplinas/update/0', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testDeletarAluno()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/disciplinas/delete/3');

        $this->delete('/disciplinas/delete/3', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/disciplinas/delete/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
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
