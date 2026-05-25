@extends('layouts.app')
@section('title', 'Matrículas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Matrículas</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Matrículas</h1>
        <div class="sp-page-hdr-sub">Vínculos entre alunos e turmas</div>
    </div>
    <a href="{{ route('matriculas.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nova Matrícula
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <div class="sp-search" style="flex:1;min-width:200px;max-width:300px">
        <i class="bi bi-search sp-search-icon"></i>
        <input type="text" name="busca" class="form-control sp-search-input"
               placeholder="Nome ou matrícula..." value="{{ request('busca') }}">
    </div>
    <select name="status" class="form-select" style="max-width:160px">
        <option value="">Todos</option>
        @foreach(['ativa','trancada','cancelada','concluida','transferida'] as $s)
        <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','status']))
    <a href="{{ route('matriculas.index') }}" class="btn btn-outline-danger" data-no-loading><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Turma</th>
                <th>Período</th>
                <th>Data</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($matriculas as $mat)
            @php
                $nome   = $mat->aluno->usuario->nome ?? '';
                $parts  = explode(' ', $nome);
                $initials = mb_strtoupper(mb_substr($parts[0],0,1) . (isset($parts[1]) ? mb_substr($parts[1],0,1) : ''));
                $colors = ['','av-green','av-amber','av-blue','av-red'];
                $color  = $colors[$mat->aluno_id % 5];
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:11px">
                        <div class="idx-avatar {{ $color }}">{{ $initials }}</div>
                        <div>
                            <div style="font-weight:600;font-size:14px">{{ $nome ?: '-' }}</div>
                            <div style="font-size:11px;color:var(--text-soft)"><code>{{ $mat->aluno->matricula ?? '' }}</code></div>
                        </div>
                    </div>
                </td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $mat->turma->nome ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $mat->periodo->nome ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $mat->data_matricula?->format('d/m/Y') }}</td>
                <td>
                    <span class="badge rounded-pill badge-status-{{ $mat->status }}" style="font-size:11px">
                        {{ $mat->status }}
                    </span>
                </td>
                <td style="text-align:right">
                    <a href="{{ route('matriculas.show', $mat) }}" class="icon-btn" title="Ver detalhes">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="sp-empty">
                        <i class="bi bi-person-check"></i>
                        <div class="sp-empty-title">Nenhuma matrícula encontrada</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('matriculas.create') }}">Realizar primeira matrícula</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($matriculas->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $matriculas->links() }}
    </div>
    @endif
</div>
@endsection
