<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CursoTest extends TestCase
{

    use DatabaseTransactions;

    CONST TOKEN_API = '123456';
    /**
     * teste  verifca se lista todos alunos
     *
     * @return void
     */
    public function testListarTodosAlunos()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/cursos');

        $token = '123456';
        $this->get('/cursos', ['Authorization' => 'Bearer ' . self::TOKEN_API]);
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
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/cursos/show/1');

        $this->get('/cursos/show/1', ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->get('/cursos/show/3', ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
    }

    /**
     * teste  cria aluno com parametros validos e tenta criar sem parametros
     *
     * @return void
     */

    public function testCriarCurso()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', '/cursos/create');

        $dados = array(
                    'nome' => 'Quae a et consequatur occaecati sint rerum.',
                    'descricao' => 'Optio sit et aliquam ullam.',
                    'conteudo' => 'Odit autem consequatur tenetur sint. Minima id architecto ipsum quaerat. Nam molestias perferendis modi laboriosam voluptatum modi ea.',
                    'valor' => '9.07'
                );
        $this->json('POST', '/cursos/create', $dados, ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(201, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);
        
        $dados = array();
        $this->json('POST', '/cursos/create', $dados, ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testEditarCurso()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('PUT', '/cursos/update/3');

        $dados = array(
                    'nome' => 'teste',
                    'descricao' => 'teste.',
                    'conteudo' => 'teste.',
                    'valor' => '9.07'
                );
        $this->json('PUT', '/cursos/update/3', $dados, ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta);

        $this->json('PUT', '/cursos/update/0', $dados, ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(404, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
        
        $dados = array();
        $this->json('PUT', '/cursos/update/3', $dados, ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    public function testDeletarCurso()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/cursos/delete/3');

        $this->delete('/cursos/delete/3', [], ['Authorization' => 'Bearer ' . self::TOKEN_API]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/cursos/delete/0', [], ['Authorization' => 'Bearer ' . self::TOKEN_API]);
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
