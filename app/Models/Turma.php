<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model {

    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'periodo_inicio','periodo_terminio','disciplina_id'];

}