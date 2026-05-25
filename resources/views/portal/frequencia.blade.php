@extends('portal.layouts.app')
@section('title', 'Frequência')

@section('content')

<h4 style="font-size:18px;font-weight:800;margin-bottom:20px">
    <i class="bi bi-calendar-check me-2" style="color:var(--purple)"></i>Frequência - {{ $aluno->nome }}
</h4>

@forelse($dados as $entry)
<div class="sp-card mb-4">
    <div class="section-label">
        {{ $entry['turma']->nome }}
        @if($entry['turma']->curso)· {{ $entry['turma']->curso->nome }}@endif
    </div>

    @forelse($entry['disciplinas'] as $d)
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <div style="font-weight:700;font-size:14px">{{ $d['disciplina']->nome }}</div>
            @if($d['pct'] !== null)
            <span style="font-size:13px;font-weight:700;color:{{ $d['pct'] >= 75 ? 'var(--green)' : ($d['pct'] >= 50 ? 'var(--amber)' : 'var(--red)') }}">
                {{ $d['pct'] }}%
            </span>
            @endif
        </div>

        <div class="d-flex gap-4 mb-2" style="font-size:12px;color:var(--text-soft)">
            <span><i class="bi bi-check-circle text-success me-1"></i>{{ $d['presentes'] }} presença(s)</span>
            <span><i class="bi bi-x-circle text-danger me-1"></i>{{ $d['ausencias'] }} falta(s)</span>
            <span>{{ $d['total'] }} total</span>
        </div>

        @if($d['pct'] !== null)
        <div class="freq-bar mb-3">
            <div class="freq-bar-fill {{ $d['pct'] >= 75 ? 'freq-ok' : ($d['pct'] >= 50 ? 'freq-warn' : 'freq-bad') }}"
                 style="width:{{ $d['pct'] }}%"></div>
        </div>
        @endif

        {{-- Aulas detalhadas --}}
        @if($d['aulas']->isNotEmpty())
        <div class="d-flex flex-wrap gap-1">
            @foreach($d['aulas'] as $aula)
            @php $presente = $aula->frequencias->first()?->presente ?? 1; @endphp
            <div title="{{ \Carbon\Carbon::parse($aula->data_aula)->format('d/m/Y') }} - {{ $presente ? 'Presente' : 'Falta' }}"
                 style="width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;
                        background:{{ $presente ? 'var(--green-bg)' : 'var(--red-bg)' }};
                        color:{{ $presente ? 'var(--green)' : 'var(--red)' }};
                        font-size:12px;cursor:default">
                {{ $presente ? '✓' : '✗' }}
            </div>
            @endforeach
        </div>
        <div style="font-size:11px;color:var(--text-soft);margin-top:6px">
            Cada quadrado = 1 aula · ✓ presente · ✗ falta
        </div>
        @endif
    </div>
    @if(!$loop->last)<hr style="border-color:var(--border);margin:16px 0">@endif
    @empty
    <p style="color:var(--text-soft);font-size:13px">Nenhuma disciplina cadastrada.</p>
    @endforelse
</div>
@empty
<div class="sp-card text-center py-5" style="color:var(--text-soft)">
    <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:10px"></i>
    <div style="font-weight:600">Nenhuma matrícula encontrada</div>
</div>
@endforelse

@endsection
