@extends('portal.layouts.app')
@section('title', 'Início')

@section('content')

{{-- Responsável: aluno selecionado --}}
@if(session('portal_tipo') === 'responsavel')
<div class="d-flex align-items-center gap-2 mb-4 p-3 sp-card" style="border-left: 4px solid var(--purple)">
    <i class="bi bi-person-circle" style="font-size:20px;color:var(--purple)"></i>
    <div>
        <div style="font-size:11px;color:var(--text-soft);text-transform:uppercase;letter-spacing:.06em">Visualizando aluno</div>
        <div style="font-weight:700;font-size:15px">{{ $aluno->nome }}</div>
    </div>
</div>
@endif

{{-- Boas-vindas --}}
<div style="background:linear-gradient(135deg,var(--purple) 0%,#9333ea 60%,#a855f7 100%);border-radius:20px;padding:24px 28px;margin-bottom:24px;position:relative;overflow:hidden">
    <div style="position:relative;z-index:1">
        <div style="font-size:12px;color:rgba(255,255,255,.7);margin-bottom:6px">
            {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </div>
        <h2 style="font-size:22px;font-weight:800;color:#fff;margin-bottom:4px">
            Olá, {{ explode(' ', $aluno->nome)[0] }}! 👋
        </h2>
        @if($matriculaAtiva)
        <p style="font-size:13px;color:rgba(255,255,255,.75)">
            {{ $matriculaAtiva->turma->curso->nome ?? '-' }} · {{ $matriculaAtiva->turma->nome ?? '-' }}
        </p>
        @endif
    </div>
    <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.06);top:-50px;right:60px"></div>
    <div style="position:absolute;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06);bottom:-40px;right:160px"></div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--purple-light);color:var(--purple)">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-num">{{ $freqPct !== null ? $freqPct.'%' : '-' }}</div>
            <div class="stat-label">Frequência</div>
        </div>
    </div>
    @if(session('portal_tipo') === 'responsavel')
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--red-bg);color:var(--red)">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-num">R$ {{ number_format($totalDevido, 2, ',', '.') }}</div>
            <div class="stat-label">A pagar</div>
        </div>
    </div>
    @endif
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--green-bg);color:var(--green)">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="stat-num">{{ $matriculaAtiva ? ucfirst($matriculaAtiva->status) : '-' }}</div>
            <div class="stat-label">Matrícula</div>
        </div>
    </div>
</div>

{{-- Cobranças pendentes (só responsável) --}}
@if(session('portal_tipo') === 'responsavel' && $cobrancasPendentes->isNotEmpty())
<div class="sp-card">
    <div class="section-label">Cobranças abertas</div>
    <table class="sp-table">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cobrancasPendentes as $c)
            @php $vencida = $c->status_real === 'vencida'; @endphp
            <tr>
                <td>{{ $c->descricao }}</td>
                <td>{{ $c->data_vencimento->format('d/m/Y') }}</td>
                <td>R$ {{ number_format($c->valor_original, 2, ',', '.') }}</td>
                <td>
                    @if($c->status_real === 'vencida')
                        <span class="badge-sp badge-red">Vencida</span>
                    @else
                        <span class="badge-sp badge-amber">Em aberto</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        <a href="{{ route('portal.cobrancas') }}" class="btn btn-sm" style="background:var(--purple-light);color:var(--purple);border:none;border-radius:8px;font-weight:600">
            Ver todas as cobranças →
        </a>
    </div>
</div>
@elseif(session('portal_tipo') === 'responsavel')
<div class="sp-card text-center py-4" style="color:var(--text-soft)">
    <i class="bi bi-check-circle" style="font-size:32px;color:var(--green);display:block;margin-bottom:8px"></i>
    <div style="font-weight:600">Nenhuma cobrança em aberto</div>
    <div style="font-size:13px">Tudo em dia! 🎉</div>
</div>
@endif

@endsection
