@extends('layouts.app')
@section('title', 'Financeiro')

@section('content')

{{-- Welcome banner --}}
<div class="welcome-banner mb-4">
    <div class="welcome-banner-text">
        <div class="date"><i class="bi bi-currency-dollar me-1"></i>Financeiro</div>
        <h2>Dashboard Financeiro</h2>
        <p>Receitas, inadimplência e cobranças em tempo real.</p>
    </div>
    <div class="d-none d-md-flex gap-2" style="position:relative;z-index:1">
        <a href="{{ route('cobrancas.gerar') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:none;border-radius:10px">
            <i class="bi bi-lightning-fill me-1"></i>Gerar Mensalidades
        </a>
        <a href="{{ route('cobrancas.create') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:none;border-radius:10px">
            <i class="bi bi-plus-lg me-1"></i>Nova Cobrança
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--green-bg)">
                <i class="bi bi-graph-up-arrow" style="color:var(--green)"></i>
            </div>
            <div class="stat-num">R$ {{ number_format($receita_mes, 2, ',', '.') }}</div>
            <div class="stat-label">Receita - {{ now()->format('m/Y') }}</div>
            <div class="stat-trend trend-up">
                <i class="bi bi-check-circle"></i> {{ $total_pago_mes }} pagamento(s)
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--blue-bg)">
                <i class="bi bi-clock" style="color:var(--blue)"></i>
            </div>
            <div class="stat-num">R$ {{ number_format($a_receber, 2, ',', '.') }}</div>
            <div class="stat-label">A Receber</div>
            <div class="stat-trend" style="color:var(--blue)">
                <i class="bi bi-arrow-right-circle"></i> {{ $total_aberto }} em aberto
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--red-bg)">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--red)"></i>
            </div>
            <div class="stat-num">R$ {{ number_format($total_vencido, 2, ',', '.') }}</div>
            <div class="stat-label">Total Vencido</div>
            <div class="stat-trend trend-down">
                <i class="bi bi-arrow-down-circle"></i> {{ $qtd_vencidas }} cobrança(s)
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--amber-bg)">
                <i class="bi bi-people-fill" style="color:var(--amber)"></i>
            </div>
            <div class="stat-num">{{ $inadimplentes->count() }}</div>
            <div class="stat-label">Inadimplentes</div>
            <div class="stat-trend" style="color:var(--amber)">
                <i class="bi bi-dot"></i> com cobrança vencida
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Receita anual --}}
    <div class="col-lg-7">
        <div class="sp-card">
            <div style="font-size:14px;font-weight:700;margin-bottom:20px">
                Receita {{ now()->year }} - Mês a Mês
            </div>
            @php
                $meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                $max_val = $receita_anual->max('total') ?: 1;
            @endphp
            <div style="display:flex;align-items:flex-end;gap:10px;height:140px">
                @foreach($meses as $idx => $mes)
                @php
                    $val = $receita_anual->get($idx + 1)?->total ?? 0;
                    $pct = $max_val > 0 ? ($val / $max_val * 100) : 0;
                    $atual = ($idx + 1) === (int)now()->format('n');
                @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
                    <div style="font-size:10px;color:var(--text-soft);white-space:nowrap">
                        @if($val > 0) R$ {{ number_format($val/1000, 1) }}k @endif
                    </div>
                    <div style="width:100%;background:{{ $atual ? 'var(--purple)' : ($val > 0 ? 'var(--purple-light)' : 'var(--border)') }};
                                border-radius:8px 8px 0 0;
                                height:{{ max(4, $pct) }}%;
                                transition:height .3s;
                                min-height:4px"></div>
                    <div style="font-size:10px;color:{{ $atual ? 'var(--purple)' : 'var(--text-soft)' }};font-weight:{{ $atual ? '700' : '400' }}">
                        {{ $mes }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Inadimplentes --}}
    <div class="col-lg-5">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div style="font-size:14px;font-weight:700">Top Inadimplentes</div>
                <a href="{{ route('negociacoes.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-handshake me-1"></i>Negociar
                </a>
            </div>
            @forelse($inadimplentes as $inad)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:34px;height:34px;border-radius:10px;background:var(--red-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-person-fill" style="color:var(--red)"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600">{{ $inad->matricula->aluno->usuario->nome ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-soft)">{{ $inad->qtd }} cobrança(s) vencida(s)</div>
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:13px;font-weight:700;color:var(--red)">R$ {{ number_format($inad->total_divida, 2, ',', '.') }}</div>
                    <a href="{{ route('negociacoes.create', ['matricula_id' => $inad->matricula_id]) }}" style="font-size:11px;color:var(--purple)">Negociar</a>
                </div>
            </div>
            @empty
            <div style="text-align:center;color:var(--green);padding:32px;font-size:14px">
                <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                Nenhum inadimplente!
            </div>
            @endforelse
        </div>
    </div>

    {{-- Últimas pagas --}}
    <div class="col-12">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div style="font-size:14px;font-weight:700">Últimos Pagamentos</div>
                <a href="{{ route('cobrancas.index', ['status'=>'paga']) }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
            </div>
            <table class="sp-table">
                <thead>
                    <tr><th>Aluno</th><th>Descrição</th><th>Pago em</th><th>Forma</th><th style="text-align:right">Valor</th></tr>
                </thead>
                <tbody>
                    @forelse($ultimas_pagas as $cob)
                    <tr>
                        <td style="font-weight:600">{{ $cob->matricula->aluno->usuario->nome ?? '-' }}</td>
                        <td style="font-size:13px;color:var(--text-soft)">{{ $cob->descricao }}</td>
                        <td style="font-size:13px">{{ $cob->data_pagamento?->format('d/m/Y') }}</td>
                        <td><span class="badge-sp badge-green">{{ ucfirst(str_replace('_',' ',$cob->forma_pagamento)) }}</span></td>
                        <td style="text-align:right;font-weight:700;color:var(--green)">R$ {{ number_format($cob->valor_pago, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-soft);padding:24px">Nenhum pagamento registrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
