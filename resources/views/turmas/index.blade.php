@extends('layouts.app')
@section('title', 'Turmas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Turmas</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Turmas</h1>
        <div class="sp-page-hdr-sub">Gerencie as turmas e seus alunos</div>
    </div>
    <a href="{{ route('turmas.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nova Turma
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <div class="sp-search" style="flex:1;min-width:200px;max-width:300px">
        <i class="bi bi-search sp-search-icon"></i>
        <input type="text" name="busca" class="form-control sp-search-input"
               placeholder="Buscar turma..." value="{{ request('busca') }}">
    </div>
    <select name="status" class="form-select" style="max-width:160px">
        <option value="">Todos os status</option>
        <option value="aberta" @selected(request('status')=='aberta')>Aberta</option>
        <option value="em_andamento" @selected(request('status')=='em_andamento')>Em andamento</option>
        <option value="encerrada" @selected(request('status')=='encerrada')>Encerrada</option>
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','status']))
    <a href="{{ route('turmas.index') }}" class="btn btn-outline-danger" data-no-loading><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Turma</th>
                <th>Curso</th>
                <th>Período</th>
                <th>Turno</th>
                <th>Vagas</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($turmas as $turma)
            <tr>
                <td style="font-weight:600">{{ $turma->nome }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $turma->curso->nome ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $turma->periodo->nome ?? '-' }}</td>
                <td>
                    <span class="badge-sp badge-muted">{{ ucfirst($turma->turno) }}</span>
                </td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $turma->vagas }}</td>
                <td>
                    <span class="badge rounded-pill badge-status-{{ $turma->status }}" style="font-size:11px">
                        {{ str_replace('_', ' ', $turma->status) }}
                    </span>
                </td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('turmas.show', $turma) }}" class="icon-btn" title="Ver detalhes">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('turmas.edit', $turma) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="sp-empty">
                        <i class="bi bi-collection"></i>
                        <div class="sp-empty-title">Nenhuma turma encontrada</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('turmas.create') }}">Criar primeira turma</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($turmas->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $turmas->links() }}
    </div>
    @endif
</div>
@endsection
