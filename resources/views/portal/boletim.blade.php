@extends('portal.layouts.app')
@section('title', 'Boletim')

@section('content')

<h4 style="font-size:18px;font-weight:800;margin-bottom:20px">
    <i class="bi bi-journal-text me-2" style="color:var(--purple)"></i>Boletim - {{ $aluno->nome }}
</h4>

@forelse($boletim as $entry)
<div class="sp-card mb-4">
    <div class="section-label">
        {{ $entry['turma']->nome }}
        @if($entry['turma']->curso)
            · {{ $entry['turma']->curso->nome }}
        @endif
        <span class="badge-sp badge-{{ $entry['matricula']->status === 'ativa' ? 'green' : 'muted' }} ms-2">
            {{ ucfirst($entry['matricula']->status) }}
        </span>
    </div>

    @forelse($entry['disciplinas'] as $d)
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div style="font-weight:700;font-size:14px">{{ $d['disciplina']->nome }}</div>
            @if($d['media'] !== null)
                <span class="badge-sp {{ $d['media'] >= 7 ? 'badge-green' : ($d['media'] >= 5 ? 'badge-amber' : 'badge-red') }}">
                    Média: {{ $d['media'] }}
                </span>
            @else
                <span class="badge-sp badge-muted">Sem notas</span>
            @endif
        </div>

        {{-- Frequência --}}
        <div class="d-flex align-items-center gap-3 mb-2" style="font-size:12px;color:var(--text-soft)">
            <span>{{ $d['presencas'] }}/{{ $d['total_aulas'] }} aulas</span>
            @if($d['freq_pct'] !== null)
            <div style="flex:1;max-width:160px">
                <div class="freq-bar">
                    <div class="freq-bar-fill {{ $d['freq_pct'] >= 75 ? 'freq-ok' : ($d['freq_pct'] >= 50 ? 'freq-warn' : 'freq-bad') }}"
                         style="width:{{ $d['freq_pct'] }}%"></div>
                </div>
            </div>
            <span style="font-weight:600;color:{{ $d['freq_pct'] >= 75 ? 'var(--green)' : ($d['freq_pct'] >= 50 ? 'var(--amber)' : 'var(--red)') }}">
                {{ $d['freq_pct'] }}%
            </span>
            @endif
        </div>

        {{-- Avaliações --}}
        @if($d['avaliacoes'])
        <div class="d-flex flex-wrap gap-2">
            @foreach($d['avaliacoes'] as $av)
            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:8px 14px;min-width:90px">
                <div style="font-size:10px;color:var(--text-soft);text-transform:uppercase;letter-spacing:.06em">
                    {{ $av['avaliacao']->titulo }}
                </div>
                <div style="font-size:18px;font-weight:800;color:{{ $av['nota'] !== null ? 'var(--text)' : 'var(--text-soft)' }}">
                    {{ $av['nota'] !== null ? number_format($av['nota'], 1, ',', '') : '-' }}
                </div>
                <div style="font-size:10px;color:var(--text-soft)">
                    / {{ number_format($av['avaliacao']->nota_maxima, 0) }}
                </div>
            </div>
            @endforeach
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
    <i class="bi bi-journal-x" style="font-size:40px;display:block;margin-bottom:10px"></i>
    <div style="font-weight:600">Nenhuma matrícula encontrada</div>
</div>
@endforelse

@endsection
