<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplinasCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplinas_cursos', function (Blueprint $table) {
            #$table->id();
            $table->integer('id')->autoIncrement();
            $table->integer('curso_id');
            $table->integer('disciplina_id');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
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
        Schema::dropIfExists('disciplinas_cursos');
    }
}
