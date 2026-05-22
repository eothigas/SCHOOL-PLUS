<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PeriodoLetivo extends Model
{
    use BelongsToTenant;

    protected $table = 'periodos_letivos';
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'nome', 'data_inicio', 'data_fim', 'status'];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim'    => 'date',
    ];

    public function turmas()
    {
        return $this->hasMany(Turma::class, 'periodo_id');
    }
}
