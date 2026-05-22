<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cobranca extends Model
{
    use BelongsToTenant;

    protected $table = 'cobrancas';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'matricula_id', 'plano_id', 'descricao',
        'valor_original', 'valor_desconto', 'valor_acrescimo', 'valor_pago',
        'data_vencimento', 'data_pagamento', 'competencia',
        'status', 'forma_pagamento', 'gateway_id', 'gateway_url', 'obs',
    ];

    protected $casts = [
        'data_vencimento'  => 'date',
        'data_pagamento'   => 'date',
        'criado_em'        => 'datetime',
        'atualizado_em'    => 'datetime',
        'valor_original'   => 'float',
        'valor_desconto'   => 'float',
        'valor_acrescimo'  => 'float',
        'valor_pago'       => 'float',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function plano()
    {
        return $this->belongsTo(PlanoPagamento::class, 'plano_id');
    }

    // Valor líquido (original - desconto + acréscimo)
    public function getValorLiquidoAttribute(): float
    {
        return $this->valor_original - $this->valor_desconto + $this->valor_acrescimo;
    }

    // Status real: aberta pode estar "vencida" pelo calendário
    public function getStatusRealAttribute(): string
    {
        if ($this->status === 'aberta' && $this->data_vencimento->isPast()) {
            return 'vencida';
        }
        return $this->status;
    }

    // Valor corrigido com multa+juros (para cobranças vencidas)
    public function getValorCorrigidoAttribute(): float
    {
        if ($this->status !== 'aberta' || !$this->data_vencimento->isPast()) {
            return $this->valor_liquido;
        }
        $dias      = $this->data_vencimento->diffInDays(today());
        $multa_pct = $this->plano?->multa_pct ?? 2.0;
        $juros_pct = $this->plano?->juros_dia_pct ?? 0.0333;
        $acrescimo = $this->valor_original * $multa_pct / 100;
        $acrescimo += $this->valor_original * $juros_pct / 100 * $dias;
        return round($this->valor_original - $this->valor_desconto + $acrescimo, 2);
    }

    // Query scopes
    public function scopeAberta($q)     { return $q->where('status', 'aberta'); }
    public function scopePaga($q)       { return $q->where('status', 'paga'); }
    public function scopeVencida($q)    { return $q->where('status', 'aberta')->where('data_vencimento', '<', today()); }
    public function scopeAVencer($q)    { return $q->where('status', 'aberta')->where('data_vencimento', '>=', today()); }
}
