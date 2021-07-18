<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AlunosCursoTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * teste  verifca se lista todos alunos por curso
     *
     * @return void
     */
    public function testListarTodosAlunosCurso()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/cursos/curso/4/alunos');

        $this->get('/cursos/curso/4/alunos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);

        $this->get('/cursos/curso/0/alunos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }
    
    /**
     * teste  verifca se encontra a turma e também se não retorna a turma
     *
     * @return void
     */
    public function testListarTodasCursosAluno()
    {   
        $this->verificaRotaEstaBloqueadaParaTokenErrado('GET', '/alunos/aluno/3/cursos');

        $this->get('/alunos/aluno/3/cursos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('id', $conteudoResposta[0]);

        $this->get('/alunos/aluno/0/cursos', ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);
    }

    /**
     * teste  cria turma com parametros validos e tenta criar sem parametros
     *
     * @dataProvider providerInserirAluno
     */
    public function testInserirAluno($dados, $curso, $httpCode, $key)
    {   
        $uri = '/cursos/curso/'.$curso.'/alunos';

        $this->verificaRotaEstaBloqueadaParaTokenErrado('POST', $uri);

        $this->json('POST', $uri, $dados, ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals($httpCode, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey($key, $conteudoResposta);

    }

    public function providerInserirAluno()
    {   
        return [
            'inserirAluno'                  => ['dados' => ["aluno_id" => 8], 'curso' => 4, 'httpCode' => 201, 'key' => 'id'],
            'inserirAlunoExistenteAluno'    => ['dados' => ["aluno_id" => 3], 'curso' => 8, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoInexistenteCurso'  => ['dados' => ["aluno_id" => 8], 'curso' => 0, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoInexistenteAluno'  => ['dados' => ["aluno_id" => 0], 'curso' => 4, 'httpCode' => 400, 'key' => 'error'],
            'inserirAlunoSemDados'          => ['dados' => [], 'curso' => 4, 'httpCode' => 400, 'key' => 'error']
        ];
    }

    public function testRemoverAlunoCurso()
    {
        $this->verificaRotaEstaBloqueadaParaTokenErrado('DELETE', '/cursos/curso/2/alunos/2');

        $this->delete('/cursos/curso/4/alunos/3', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEmpty($this->response->content());

        $this->delete('/cursos/curso/4/alunos/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
        $this->assertEquals(400, $this->response->status());
        $conteudoResposta = $this->isJsonResponse();
        $this->assertArrayHasKey('error', $conteudoResposta);

        $this->delete('/cursos/curso/0/alunos/0', [], ['Authorization' => 'Bearer ' . env('TOKEN_API')]);
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
