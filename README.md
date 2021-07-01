# API REST
## Lumen PHP Framework

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

# ROTAS 

`{{urlapilumen}} = https://localhost`

## cursos

#### GET - listar todos os cursos

`{{urlapilumen}}/cursos`

#### GET - mostar curso

`{{urlapilumen}}/cursos/show/{idCurso}`

#### POST criar curso

`{{urlapilumen}}/cursos/create`

##### Request Headers

###### Content-Type application/json

##### Body raw (json)

    {
      "nome": "Quae a et consequatur occaecati sint rerum.",
      "descricao": "Optio sit et aliquam ullam.",
      "conteudo": "Odit autem consequatur tenetur sint. Minima id architecto ipsum quaerat. Nam molestias perferendis modi laboriosam voluptatum modi ea.",
      "valor": "9.07"
    }

#### PUT - editar curso

`{{urlapilumen}}/cursos/update/{idCurso}`

###### Content-Type application/json

##### Body raw (json)

    {
    "nome" : "teste",
    "descricao": "teste"
    }

#### DELETE - deletar curso

`{{urlapilumen}}/cursos/delete/{idCurso}`

## alunos

#### GET - listar todos os alunos

`{{urlapilumen}}/alunos`

#### GET - mostrar aluno

`{{urlapilumen}}/alunos/show/{idAluno}`

#### POST - criar aluno

`{{urlapilumen}}/alunos/create`

###### Content-Type application/json

##### Body raw (json)

    {
        "nome": "Fernando Soares Franco",
        "cpf": "01779930021",
        "idade": 23,
        "data_nascimento": "1998-03-20",
        "matricula": "5555"
    }

#### PUT - editar aluno

`{{urlapilumen}}/alunos/update/{idAluno}`

###### Content-Type application/json

##### Body raw (json)
    
    {
        "nome": "Fernando FRANCO",
        "cpf": "01323222",
        "idade": 27
    }

#### DELETE - deletar aluno

`{{urlapilumen}}/alunos/delete/{idAluno}`


## disciplinas

#### GET - listar todos as disciplinas

`{{urlapilumen}}/disciplinas`

#### GET - mostrar disciplina

`{{urlapilumen}}/disciplinas/show/{idDisciplina}`

#### POST - criar disciplina

`{{urlapilumen}}/disciplinas/create`

###### Content-Type application/json

##### Body raw (json)

    {
        "nome": "Analise de sistema"
    }

#### PUT - editar disciplina

`{{urlapilumen}}/disciplinas/update/{idDisciplina}`

###### Content-Type application/json

##### Body raw (json)

    {
        "nome" : "ADS"
    }

#### DELETE - deletar disciplina

`{{urlapilumen}}/disciplinas/delete/{idDisciplina}`



## turmas

### GET - listar todas as turmas

`{{urlapilumen}}/turmas`

### GET - mostrar turma

`{{urlapilumen}}/turmas/show/{idTurma}`

#### POST - criar turma

`{{urlapilumen}}/turmas/create`

###### Content-Type application/json

##### Body raw (json)

    {
        "nome": "turma 11220 w",
        "descricao": "ciência da computação",
        "periodo_inicio": "2021-02-01",
        "periodo_terminio": "2021-02-01",
        "disciplina_id": 2
    }

#### PUT - editar turma

`{{urlapilumen}}/turmas/create`

###### Content-Type application/json

##### Body raw (json)

    {
        "nome": "turma eee w",
        "descricao": "turma turma",
        "periodo_inicio": "2022-02-01",
        "periodo_terminio": "2022-02-01",
        "disciplina_id": 2
    }

#### DELETE - deletar turma

`{{urlapilumen}}/turmas/delete/{idTurma}`


## relação entre cursos e alunos

#### GET - listar todos os alunos do curso

`{{urlapilumen}}/cursos/curso/{idCurso}/alunos`

#### GET - listar todos os curso do aluno

`{{urlapilumen}}/alunos/aluno/{idAluno}/turmas`

#### POST - inserir aluno em curso

`{{urlapilumen}}/cursos/curso/{idCurso}/alunos`

###### Content-Type application/json

##### Body raw (json)

    {
        "aluno_id": 3
    }
    
#### DELETE - remover aluno do curso

`{{urlapilumen}}/cursos/curso/{idCurso}/aluno/{idAluno}`


## relação entre cursos e alunos

#### GET - listar todos os alunos da turma

`{{urlapilumen}}/turmas/turma/{idTurma}/alunos`

#### GET - listar todas as turmas do aluno

`{{urlapilumen}}/alunos/aluno/{idAluno}/turmas`

#### POST - inserir aluno em turma

`{{urlapilumen}}/turmas/turma/{idTurma}/alunos`

###### Content-Type application/json

##### Body raw (json)

    {
        "aluno_id": 3
    }
    
#### DELETE - remover aluno da turma

`{{urlapilumen}}/turmas/turma/{idTurma}/alunos/{idAluno}`



## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
