<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunosCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos_cursos', function (Blueprint $table) {
            #$table->id();
            $table->integer('id')->autoIncrement();
            $table->integer('curso_id');
            $table->integer('aluno_id');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos_cursos');
    }
}
