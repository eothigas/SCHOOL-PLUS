<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use BelongsToTenant;

    protected $table = 'avaliacoes';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'turma_disc_id', 'nome', 'tipo',
        'peso', 'nota_maxima', 'data_aplicacao',
    ];

    protected $casts = [
        'data_aplicacao' => 'date',
        'peso'           => 'float',
        'nota_maxima'    => 'float',
        'criado_em'      => 'datetime',
    ];

    public function turmaDisiplina()
    {
        return $this->belongsTo(TurmaDisiplina::class, 'turma_disc_id');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'avaliacao_id');
    }
}
