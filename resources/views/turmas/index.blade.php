@extends('layouts.app')
@section('title', 'Turmas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Turmas</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Turmas</h4>
    <a href="{{ route('turmas.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nova Turma
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <input type="text" name="busca" class="form-control" style="max-width:300px"
           placeholder="Buscar turma..." value="{{ request('busca') }}">
    <select name="status" class="form-select" style="max-width:160px">
        <option value="">Todos os status</option>
        <option value="aberta" @selected(request('status')=='aberta')>Aberta</option>
        <option value="em_andamento" @selected(request('status')=='em_andamento')>Em andamento</option>
        <option value="encerrada" @selected(request('status')=='encerrada')>Encerrada</option>
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','status']))
    <a href="{{ route('turmas.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-sp table-hover mb-0">
            <thead>
                <tr>
                    <th>Turma</th>
                    <th>Curso</th>
                    <th>Período</th>
                    <th>Turno</th>
                    <th>Vagas</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turmas as $turma)
                <tr>
                    <td class="fw-semibold">{{ $turma->nome }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $turma->curso->nome ?? '—' }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $turma->periodo->nome ?? '—' }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($turma->turno) }}</span></td>
                    <td class="text-muted">{{ $turma->vagas }}</td>
                    <td>
                        <span class="badge rounded-pill badge-status-{{ $turma->status }}" style="font-size:10px">
                            {{ str_replace('_', ' ', $turma->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('turmas.show', $turma) }}" class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('turmas.edit', $turma) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        Nenhuma turma encontrada.
                        <a href="{{ route('turmas.create') }}">Criar primeira turma</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($turmas->hasPages())
    <div class="p-3 border-top" style="border-color:#1e2d47!important">
        {{ $turmas->links() }}
    </div>
    @endif
</div>
@endsection
