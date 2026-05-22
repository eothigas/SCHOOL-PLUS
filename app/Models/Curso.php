<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use BelongsToTenant;

    protected $table = 'cursos';
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'nome', 'tipo', 'duracao_meses', 'descricao', 'status'];

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }
}
