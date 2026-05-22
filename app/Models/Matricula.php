<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use BelongsToTenant;

    protected $table = 'matriculas';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'aluno_id', 'turma_id', 'periodo_id',
        'data_matricula', 'status', 'obs',
    ];

    protected $casts = [
        'data_matricula' => 'date',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function periodo()
    {
        return $this->belongsTo(PeriodoLetivo::class, 'periodo_id');
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    public function cobrancas()
    {
        return $this->hasMany(Cobranca::class);
    }

    public function negociacoes()
    {
        return $this->hasMany(Negociacao::class);
    }
}
