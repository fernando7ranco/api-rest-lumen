<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model {

    use HasFactory;

    protected $fillable = ['nome', 'cpf', 'idade','data_nascimento','matricula'];

    public function cpfJaExiste($cpf){
        return $this->where("cpf", $cpf)->count();
    }
}