<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use BelongsToTenant;

    protected $table = 'turmas';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'curso_id', 'periodo_id',
        'nome', 'turno', 'vagas', 'sala', 'status',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function periodo()
    {
        return $this->belongsTo(PeriodoLetivo::class, 'periodo_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'turma_disciplinas')
            ->withPivot('professor_id');
    }

    public function turmaDisiplinas()
    {
        return $this->hasMany(TurmaDisiplina::class);
    }
}
