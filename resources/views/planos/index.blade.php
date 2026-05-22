@extends('layouts.app')
@section('title', 'Planos de Pagamento')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Planos de Pagamento</h4>
        <div style="font-size:13px;color:var(--text-soft)">Gerencie os planos de mensalidade e suas condições</div>
    </div>
    <a href="{{ route('planos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Plano
    </a>
</div>

@if($planos->isEmpty())
<div class="sp-card" style="text-align:center;padding:60px">
    <i class="bi bi-credit-card" style="font-size:48px;color:var(--border);display:block;margin-bottom:16px"></i>
    <div style="font-size:15px;font-weight:700;color:var(--text-soft)">Nenhum plano cadastrado</div>
    <div style="font-size:13px;color:var(--text-soft);margin-top:4px">Crie um plano para começar a gerar cobranças</div>
    <a href="{{ route('planos.create') }}" class="btn btn-primary mt-4">
        <i class="bi bi-plus-lg me-1"></i>Criar primeiro plano
    </a>
</div>
@else
<div class="row g-3">
    @foreach($planos as $plano)
    <div class="col-md-6 col-xl-4">
        <div class="sp-card h-100" style="position:relative">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
                <div>
                    <div style="font-size:15px;font-weight:800;color:var(--text)">{{ $plano->nome }}</div>
                    @if($plano->curso)
                    <div style="font-size:11px;color:var(--text-soft);margin-top:2px">{{ $plano->curso->nome }}</div>
                    @endif
                </div>
                @php
                    $tipoBadge = match($plano->tipo) {
                        'mensal'     => 'badge-blue',
                        'semestral'  => 'badge-purple',
                        'anual'      => 'badge-green',
                        default      => 'badge-muted',
                    };
                @endphp
                <span class="badge-sp {{ $tipoBadge }}">{{ ucfirst($plano->tipo) }}</span>
            </div>

            <div style="font-size:32px;font-weight:900;color:var(--purple);margin-bottom:16px;line-height:1">
                R$ {{ number_format($plano->valor, 2, ',', '.') }}
                <span style="font-size:12px;font-weight:400;color:var(--text-soft)">/{{ $plano->tipo === 'mensal' ? 'mês' : $plano->tipo }}</span>
            </div>

            <dl class="row mb-0" style="font-size:12px;row-gap:6px">
                <dt class="col-7" style="color:var(--text-soft);font-weight:500">Vencimento</dt>
                <dd class="col-5 fw-semibold">Dia {{ $plano->dia_vencimento }}</dd>

                @if($plano->desconto_pct > 0)
                <dt class="col-7" style="color:var(--text-soft);font-weight:500">Desconto</dt>
                <dd class="col-5 fw-semibold" style="color:var(--green)">{{ $plano->desconto_pct }}%</dd>
                @endif

                <dt class="col-7" style="color:var(--text-soft);font-weight:500">Multa por atraso</dt>
                <dd class="col-5 fw-semibold">{{ $plano->multa_pct ?? 2 }}%</dd>

                <dt class="col-7" style="color:var(--text-soft);font-weight:500">Juros diário</dt>
                <dd class="col-5 fw-semibold">{{ number_format($plano->juros_dia_pct ?? 0.0333, 4) }}%</dd>
            </dl>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('planos.edit', $plano) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <form method="POST" action="{{ route('planos.destroy', $plano) }}"
                      onsubmit="return confirm('Desativar este plano?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-archive me-1"></i>Desativar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
