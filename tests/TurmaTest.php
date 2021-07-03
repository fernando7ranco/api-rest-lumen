<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TurmaTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * teste  verifca se lista todos turmas
     *
     * @return void
     */
    public function testListarTodosTurmas()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/turmas');

        $this->get('/turmas', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);
    }
    
    /**
     * teste  verifca se encontra a turma e também se não retorna a turma
     *
     * @return void
     */
    public function testMostrarTurma()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/turmas/show/1');

        $this->get('/turmas/show/1', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->get('/turmas/show/3', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
    }

    /**
     * teste  cria turma com parametros validos e tenta criar sem parametros
     *
     * @return void
     */
    public function testCriarAluno()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', '/turmas/create');

        $dados = array(
                'nome' => 'turma 11220 w',
                'descricao' => 'ciência da computação',
                'periodo_inicio' => '2021-02-01',
                'periodo_terminio' => '2021-02-01',
                'disciplina_id' => 2
            );

        $this->json('POST', '/turmas/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(201, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $dados['disciplina_id'] = 0;
        $this->json('POST', '/turmas/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
        
        $dados = array();
        $this->json('POST', '/turmas/create', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testEditarTurma()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('PUT', '/turmas/update/3');

        $dados = array(
                'nome' => 'turma 11220 w',
                'descricao' => 'ciência da computação',
                'periodo_inicio' => '2021-02-01',
                'periodo_terminio' => '2021-02-01',
                'disciplina_id' => 2
            );
        $this->json('PUT', '/turmas/update/3', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $dados['disciplina_id'] = 0;
        $this->json('PUT', '/turmas/update/3', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->json('PUT', '/turmas/update/0', $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testDeletarTurma()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/turmas/delete/3');

        $this->delete('/turmas/delete/3', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/turmas/delete/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
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
