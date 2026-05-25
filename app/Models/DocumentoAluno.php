<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DocumentoAluno extends Model
{
    use BelongsToTenant;

    protected $table = 'documentos_aluno';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'aluno_id', 'tipo', 'nome', 'arquivo_url',
    ];

    protected $casts = [
        'criado_em' => 'datetime',
    ];

    public static $tiposLabel = [
        'rg'                    => 'RG',
        'cpf'                   => 'CPF',
        'comprovante_residencia' => 'Comp. Residência',
        'foto'                  => 'Foto',
        'historico'             => 'Histórico Escolar',
        'declaracao'            => 'Declaração',
        'outro'                 => 'Outro',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::$tiposLabel[$this->tipo] ?? $this->tipo;
    }
}
