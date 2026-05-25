@extends('layouts.app')
@section('title', 'Cobrança')

@section('content')
@php
    $sr = $cobranca->status_real;
    $badge = match($sr) {
        'paga'      => 'badge-green',
        'vencida'   => 'badge-red',
        'cancelada' => 'badge-muted',
        'negociada' => 'badge-blue',
        default     => 'badge-amber',
    };
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Cobrança #{{ $cobranca->id }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">{{ $cobranca->descricao }}</div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge-sp {{ $badge }}" style="font-size:13px;padding:6px 14px">
            {{ ucfirst($sr) }}
        </span>
        <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Info da cobrança --}}
    <div class="col-md-5">
        <div class="sp-card mb-3">
            <div class="section-label">Dados da Cobrança</div>
            <dl class="row mb-0" style="font-size:14px;row-gap:8px">
                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Aluno</dt>
                <dd class="col-7 fw-semibold">{{ $cobranca->matricula->aluno->usuario->nome ?? '-' }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Plano</dt>
                <dd class="col-7">{{ $cobranca->plano?->nome ?? '-' }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Vencimento</dt>
                <dd class="col-7 {{ $sr==='vencida' ? 'text-danger fw-bold' : '' }}">{{ $cobranca->data_vencimento->format('d/m/Y') }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Competência</dt>
                <dd class="col-7">{{ $cobranca->competencia ?? '-' }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Valor Original</dt>
                <dd class="col-7 fw-bold">R$ {{ number_format($cobranca->valor_original, 2, ',', '.') }}</dd>

                @if($cobranca->valor_desconto > 0)
                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Desconto</dt>
                <dd class="col-7" style="color:var(--green)">- R$ {{ number_format($cobranca->valor_desconto, 2, ',', '.') }}</dd>
                @endif

                @if($sr === 'vencida')
                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Valor Corrigido</dt>
                <dd class="col-7 fw-bold" style="color:var(--red)">R$ {{ number_format($cobranca->valor_corrigido, 2, ',', '.') }}</dd>
                @endif

                @if($cobranca->status === 'paga')
                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Pago em</dt>
                <dd class="col-7">{{ $cobranca->data_pagamento?->format('d/m/Y') }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Forma</dt>
                <dd class="col-7">{{ ucfirst(str_replace('_',' ',$cobranca->forma_pagamento)) }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Valor Pago</dt>
                <dd class="col-7 fw-bold" style="color:var(--green)">R$ {{ number_format($cobranca->valor_pago, 2, ',', '.') }}</dd>
                @endif
            </dl>
        </div>

        @if($cobranca->obs)
        <div class="sp-card" style="background:var(--surface2)">
            <div style="font-size:12px;color:var(--text-soft);margin-bottom:4px">Observação</div>
            <div style="font-size:14px">{{ $cobranca->obs }}</div>
        </div>
        @endif
    </div>

    {{-- Ações --}}
    <div class="col-md-7">

        {{-- Registrar pagamento --}}
        @if(in_array($cobranca->status, ['aberta']))
        <div class="sp-card mb-3">
            <div style="font-size:14px;font-weight:700;margin-bottom:16px">
                <i class="bi bi-cash-stack me-2" style="color:var(--green)"></i>Registrar Pagamento
            </div>
            <form method="POST" action="{{ route('cobrancas.pagar', $cobranca) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Forma de Pagamento *</label>
                        <select name="forma_pagamento" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="pix">PIX</option>
                            <option value="boleto">Boleto</option>
                            <option value="cartao_credito">Cartão de Crédito</option>
                            <option value="cartao_debito">Cartão de Débito</option>
                            <option value="transferencia">Transferência</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Valor Pago *</label>
                        <input type="number" name="valor_pago" class="form-control" step="0.01" min="0.01"
                               value="{{ $cobranca->valor_corrigido }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Pagamento *</label>
                        <input type="date" name="data_pagamento" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
        @endif

        @if($cobranca->status === 'paga')
        <div class="sp-card mb-3" style="background:var(--green-bg);border-color:#86efac">
            <div style="display:flex;align-items:center;gap:14px">
                <div style="width:48px;height:48px;border-radius:14px;background:var(--green);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-check-lg" style="color:#fff;font-size:22px"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:800;color:var(--green)">Pagamento Confirmado</div>
                    <div style="font-size:13px;color:var(--green)">
                        R$ {{ number_format($cobranca->valor_pago, 2, ',', '.') }} via {{ ucfirst(str_replace('_',' ',$cobranca->forma_pagamento)) }}
                        em {{ $cobranca->data_pagamento?->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Ações secundárias --}}
        @if(in_array($cobranca->status, ['aberta']))
        <div class="sp-card" style="background:var(--surface2)">
            <div style="font-size:13px;color:var(--text-soft);margin-bottom:12px">Outras Ações</div>
            <div class="d-flex gap-2">
                <a href="{{ route('negociacoes.create', ['matricula_id' => $cobranca->matricula_id]) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-handshake me-1"></i>Negociar
                </a>
                <form method="POST" action="{{ route('cobrancas.cancelar', $cobranca) }}"
                      onsubmit="return confirm('Cancelar esta cobrança?')">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Cancelar Cobrança
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
