<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use BelongsToTenant;

    protected $table = 'alunos';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'usuario_id', 'matricula',
        'data_nascimento', 'sexo', 'rg', 'cpf',
        'naturalidade', 'nacionalidade',
        'nome_pai', 'nome_mae',
        'endereco', 'cidade', 'estado', 'cep',
        'necessidade_especial', 'desc_necessidade',
    ];

    protected $casts = [
        'data_nascimento'     => 'date',
        'necessidade_especial' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function responsaveis()
    {
        return $this->hasMany(Responsavel::class);
    }

    // Nome do aluno via relação com usuario
    public function getNomeAttribute(): string
    {
        return $this->usuario?->nome ?? '—';
    }

    public function getEmailAttribute(): string
    {
        return $this->usuario?->email ?? '—';
    }
}
