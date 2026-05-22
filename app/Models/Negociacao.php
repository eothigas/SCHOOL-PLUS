<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Negociacao extends Model
{
    use BelongsToTenant;

    protected $table = 'negociacoes';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'matricula_id', 'usuario_id',
        'valor_total', 'desconto_pct', 'qtd_parcelas', 'obs',
    ];

    protected $casts = [
        'valor_total'   => 'float',
        'desconto_pct'  => 'float',
        'criado_em'     => 'datetime',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function getValorComDescontoAttribute(): float
    {
        return round($this->valor_total * (1 - $this->desconto_pct / 100), 2);
    }

    public function getValorParcelaAttribute(): float
    {
        return $this->qtd_parcelas > 0
            ? round($this->valor_com_desconto / $this->qtd_parcelas, 2)
            : $this->valor_com_desconto;
    }
}
