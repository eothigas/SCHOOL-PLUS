@extends('layouts.app')
@section('title', 'Matrículas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Matrículas</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Matrículas</h4>
    <a href="{{ route('matriculas.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nova Matrícula
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <input type="text" name="busca" class="form-control" style="max-width:300px"
           placeholder="Nome ou matrícula do aluno..." value="{{ request('busca') }}">
    <select name="status" class="form-select" style="max-width:160px">
        <option value="">Todos</option>
        @foreach(['ativa','trancada','cancelada','concluida','transferida'] as $s)
        <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','status']))
    <a href="{{ route('matriculas.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-sp table-hover mb-0">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Turma</th>
                    <th>Período</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matriculas as $mat)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $mat->aluno->usuario->nome ?? '—' }}</div>
                        <small class="text-muted"><code>{{ $mat->aluno->matricula ?? '' }}</code></small>
                    </td>
                    <td class="text-muted" style="font-size:13px">{{ $mat->turma->nome ?? '—' }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $mat->periodo->nome ?? '—' }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $mat->data_matricula?->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge rounded-pill badge-status-{{ $mat->status }}" style="font-size:10px">
                            {{ $mat->status }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('matriculas.show', $mat) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        Nenhuma matrícula encontrada.
                        <a href="{{ route('matriculas.create') }}">Realizar primeira matrícula</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($matriculas->hasPages())
    <div class="p-3 border-top" style="border-color:#1e2d47!important">
        {{ $matriculas->links() }}
    </div>
    @endif
</div>
@endsection
