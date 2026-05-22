<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PlanoPagamento extends Model
{
    use BelongsToTenant;

    protected $table = 'planos_pagamento';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'curso_id', 'nome', 'tipo', 'valor',
        'dia_vencimento', 'desconto_pct', 'multa_pct', 'juros_dia_pct', 'status',
    ];

    protected $casts = [
        'valor'         => 'float',
        'desconto_pct'  => 'float',
        'multa_pct'     => 'float',
        'juros_dia_pct' => 'float',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function cobrancas()
    {
        return $this->hasMany(Cobranca::class, 'plano_id');
    }
}
