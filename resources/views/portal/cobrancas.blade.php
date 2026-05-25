@extends('portal.layouts.app')
@section('title', 'Financeiro')

@section('content')

<h4 style="font-size:18px;font-weight:800;margin-bottom:20px">
    <i class="bi bi-receipt me-2" style="color:var(--purple)"></i>Financeiro - {{ $aluno->nome }}
</h4>

{{-- Totais --}}
<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--red-bg);color:var(--red)">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="stat-num" style="font-size:22px">R$ {{ number_format($totalAberto, 2, ',', '.') }}</div>
            <div class="stat-label">Em aberto</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--green-bg);color:var(--green)">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-num" style="font-size:22px">R$ {{ number_format($totalPago, 2, ',', '.') }}</div>
            <div class="stat-label">Total pago</div>
        </div>
    </div>
</div>

{{-- Lista --}}
<div class="sp-card">
    <div class="section-label">Todas as cobranças</div>

    @forelse($cobrancas as $c)
    @php
        $statusLabel = match($c->status_real) {
            'paga'      => ['Pago', 'badge-green'],
            'cancelada' => ['Cancelada', 'badge-muted'],
            'negociada' => ['Negociada', 'badge-blue'],
            'vencida'   => ['Vencida', 'badge-red'],
            default     => ['Em aberto', 'badge-amber'],
        };
    @endphp
    <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}"
         style="border-color:var(--border)!important">
        <div style="flex:1;min-width:0">
            <div style="font-weight:600;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ $c->descricao }}
            </div>
            <div style="font-size:12px;color:var(--text-soft)">
                Vencimento: {{ $c->data_vencimento->format('d/m/Y') }}
                @if($c->status === 'paga' && $c->data_pagamento)
                · Pago em {{ $c->data_pagamento->format('d/m/Y') }}
                @endif
            </div>
        </div>
        <div class="text-end ms-3" style="flex-shrink:0">
            <div style="font-size:16px;font-weight:800">
                R$ {{ number_format($c->valor_original, 2, ',', '.') }}
            </div>
            <span class="badge-sp {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
        </div>
    </div>
    @empty
    <div class="text-center py-4" style="color:var(--text-soft)">
        <i class="bi bi-receipt" style="font-size:32px;display:block;margin-bottom:8px"></i>
        Nenhuma cobrança registrada.
    </div>
    @endforelse
</div>

@endsection
