<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AlunosTurmaTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * teste  verifca se lista todos os alunos por turma
     *
     * @return void
     */
    public function testListarTodosAlunosTurmas()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/turmas/turma/3/alunos');

        $this->get('/turmas/turma/3/alunos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);


        $this->get('/turmas/turma/0/alunos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }
    
    /**
     * teste  verifca se encontra a turma e também se não retorna a turma
     *
     * @return void
     */
    public function testListarTodasTurmasAluno()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/alunos/aluno/3/turmas');

        $this->get('/alunos/aluno/0/turmas', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->get('/alunos/aluno/3/turmas', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);
    }

    /**
     * teste  cria turma com parametros validos e tenta criar sem parametros
     *
     * @dataProvider providerInserirAluno
     */
    public function testInserirAluno($dados, $turma, $httpCode, $key)
    {   
        $uri = '/turmas/turma/'.$turma.'/alunos';

        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', $uri);

        $this->json('POST', $uri, $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals($httpCode, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey($key, $conteudoResposta);

    }

    public function providerInserirAluno()
    {   
        return [
            'inserirAluno'                  => ['dados' => ["aluno_id" => 4], 'turma' => 4, 'httpCode' => 201, 'key' => 'id'],
            'inserirAlunoExistenteAluno'    => ['dados' => ["aluno_id" => 4], 'turma' => 3, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoInexistenteTurma'  => ['dados' => ["aluno_id" => 4], 'turma' => 0, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoInexistenteAluno'  => ['dados' => ["aluno_id" => 0], 'turma' => 4, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoSemDados'          => ['dados' => [], 'turma' => 4, 'httpCode' => 400, 'key' => 'error']
        ];
    }

    public function testRemoverAlunoTurma()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/turmas/turma/2/alunos/2');

        $this->delete('/turmas/turma/3/alunos/3', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/turmas/turma/3/alunos/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->delete('/turmas/turma/0/alunos/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
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
