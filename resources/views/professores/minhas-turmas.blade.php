@extends('layouts.app')
@section('title', 'Minhas Turmas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Minhas Turmas</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Minhas Turmas</h1>
        <div class="sp-page-hdr-sub">Suas turmas e disciplinas atribuídas</div>
    </div>
</div>

@forelse($turmaDiscs as $turmaId => $discs)
@php
    $turma = $discs->first()->turma;
@endphp
<div class="sp-card mb-3" style="padding:0">
    {{-- Turma header --}}
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px">
        <div style="width:42px;height:42px;border-radius:12px;background:var(--purple-light);
                    display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-grid-fill" style="color:var(--purple);font-size:18px"></i>
        </div>
        <div>
            <div style="font-size:15px;font-weight:800;color:var(--text)">{{ $turma->nome ?? 'Turma' }}</div>
            <div style="font-size:12px;color:var(--text-soft)">
                {{ $turma->curso->nome ?? '' }}
                @if($turma->periodo)
                &bull; {{ $turma->periodo->nome }}
                @endif
                @if($turma->turno)
                &bull; <span class="badge-sp badge-muted">{{ ucfirst($turma->turno) }}</span>
                @endif
            </div>
        </div>
        <div class="ms-auto">
            <span class="badge-sp badge-purple">{{ $discs->count() }} {{ $discs->count() == 1 ? 'disciplina' : 'disciplinas' }}</span>
        </div>
    </div>

    {{-- Disciplinas --}}
    <table class="sp-table">
        <thead>
            <tr>
                <th>Disciplina</th>
                <th style="width:120px">Aulas</th>
                <th style="width:160px">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discs as $td)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:34px;height:34px;border-radius:9px;background:var(--blue-bg);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-journal-text" style="color:var(--blue);font-size:14px"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700">{{ $td->disciplina->nome ?? '—' }}</div>
                            @if($td->disciplina?->carga_horaria)
                            <div style="font-size:11px;color:var(--text-soft)">{{ $td->disciplina->carga_horaria }}h</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @php $totalAulas = $td->aulas_count ?? 0; @endphp
                    <span style="font-size:13px;font-weight:700;color:var(--text)">{{ $totalAulas }}</span>
                    <span style="font-size:11px;color:var(--text-soft);margin-left:2px">aulas</span>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('diario.index', $td->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-journal-text me-1"></i>Abrir Diário
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@empty
<div class="sp-card">
    <div class="sp-empty">
        <i class="bi bi-journal-x"></i>
        <div class="sp-empty-title">Nenhuma turma atribuída</div>
        <div class="sp-empty-sub">Aguarde a atribuição de turmas e disciplinas pela secretaria.</div>
    </div>
</div>
@endforelse

@endsection
