<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurmaDisiplina extends Model
{
    protected $table = 'turma_disciplinas';
    public $timestamps = false;

    protected $fillable = ['turma_id', 'disciplina_id', 'professor_id'];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function professor()
    {
        return $this->belongsTo(Usuario::class, 'professor_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'turma_disc_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'turma_disc_id');
    }
}
