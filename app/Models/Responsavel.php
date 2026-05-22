<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use BelongsToTenant;

    protected $table = 'responsaveis';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'aluno_id', 'nome', 'parentesco',
        'cpf', 'telefone', 'email', 'usuario_id',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
