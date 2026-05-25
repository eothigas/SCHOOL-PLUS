@extends('layouts.app')
@section('title', 'Boletim')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Boletim - {{ $aluno->usuario->nome ?? '-' }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            Desempenho acadêmico completo
        </div>
    </div>
    <a href="{{ route('alunos.show', $aluno) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

@if(empty($boletim))
<div class="sp-card" style="text-align:center;padding:48px;color:var(--text-soft)">
    <i class="bi bi-journal-x" style="font-size:48px;color:var(--border);display:block;margin-bottom:16px"></i>
    Nenhuma matrícula ativa ou dados de diário encontrados.
</div>
@endif

@foreach($boletim as $item)
<div class="sp-card mb-4" style="padding:0">
    {{-- Header da turma --}}
    <div style="padding:16px 24px;background:var(--purple);border-radius:17px 17px 0 0;display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:15px;font-weight:800;color:#fff">{{ $item['turma']->nome }}</div>
            <div style="font-size:12px;color:rgba(255,255,255,.75)">
                {{ $item['turma']->curso->nome ?? '' }}
                @if($item['matricula']->periodo) &middot; {{ $item['matricula']->periodo->nome ?? '' }} @endif
            </div>
        </div>
        <span style="background:rgba(255,255,255,.2);color:#fff;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">
            {{ ucfirst($item['matricula']->status) }}
        </span>
    </div>

    {{-- Disciplinas --}}
    @if(empty($item['disciplinas']))
    <div style="padding:24px;text-align:center;color:var(--text-soft);font-size:14px">
        Nenhuma disciplina vinculada a esta turma.
    </div>
    @endif

    @foreach($item['disciplinas'] as $disc)
    <div style="border-bottom:1px solid var(--border)">

        {{-- Sub-header da disciplina --}}
        <div style="padding:14px 24px;background:var(--surface2);display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:14px;font-weight:700;color:var(--text)">
                <i class="bi bi-journal-text me-2" style="color:var(--purple)"></i>
                {{ $disc['disciplina']->nome }}
                @if($disc['td']->professor)
                <span style="font-size:11px;color:var(--text-soft);font-weight:400;margin-left:8px">
                    &middot; {{ $disc['td']->professor->nome }}
                </span>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:16px;font-size:13px">
                {{-- Frequência --}}
                @if($disc['total_aulas'] > 0)
                <div style="text-align:center">
                    <div style="font-size:11px;color:var(--text-soft);text-transform:uppercase;letter-spacing:.05em">Frequência</div>
                    <div style="font-weight:800;color:{{ $disc['freq_pct'] >= 75 ? 'var(--green)' : 'var(--red)' }}">
                        {{ $disc['freq_pct'] }}%
                    </div>
                    <div style="font-size:10px;color:var(--text-soft)">{{ $disc['presencas'] }}/{{ $disc['total_aulas'] }} aulas</div>
                </div>
                @endif

                {{-- Média --}}
                <div style="text-align:center">
                    <div style="font-size:11px;color:var(--text-soft);text-transform:uppercase;letter-spacing:.05em">Média</div>
                    @if($disc['media'] !== null)
                    @php $cor = $disc['media'] >= 7 ? 'var(--green)' : ($disc['media'] >= 5 ? 'var(--amber)' : 'var(--red)'); @endphp
                    <div style="font-size:20px;font-weight:900;color:{{ $cor }};line-height:1">{{ $disc['media'] }}</div>
                    <div style="font-size:10px;color:{{ $cor }}">{{ $disc['media'] >= 7 ? 'Aprovado' : ($disc['media'] >= 5 ? 'Recuperação' : 'Reprovado') }}</div>
                    @else
                    <div style="font-size:16px;font-weight:700;color:var(--text-soft)">-</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Avaliações --}}
        @if(count($disc['avaliacoes']) > 0)
        <div style="padding:0 24px 16px">
            <div class="row g-2 mt-0" style="padding-top:12px">
                @foreach($disc['avaliacoes'] as $av_item)
                <div class="col-auto">
                    <div style="background:var(--surface2);border:1.5px solid var(--border);border-radius:12px;padding:10px 14px;min-width:110px;text-align:center">
                        <div style="font-size:11px;color:var(--text-soft);margin-bottom:2px">{{ $av_item['avaliacao']->nome }}</div>
                        <div style="font-size:10px;color:var(--text-soft);margin-bottom:6px">{{ ucfirst($av_item['avaliacao']->tipo) }}</div>
                        @if($av_item['nota'] !== null)
                        @php
                            $n = $av_item['nota'];
                            $max = $av_item['avaliacao']->nota_maxima;
                            $pct = $max > 0 ? $n/$max : 0;
                            $nc = $pct >= 0.7 ? 'var(--green)' : ($pct >= 0.5 ? 'var(--amber)' : 'var(--red)');
                        @endphp
                        <div style="font-size:22px;font-weight:800;color:{{ $nc }};line-height:1">{{ number_format($n, 1) }}</div>
                        <div style="font-size:10px;color:var(--text-soft)">/{{ number_format($max, 0) }}</div>
                        @else
                        <div style="font-size:18px;font-weight:700;color:var(--text-soft)">-</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="padding:12px 24px;font-size:13px;color:var(--text-soft)">Nenhuma avaliação ainda.</div>
        @endif

    </div>
    @endforeach
</div>
@endforeach

@endsection
