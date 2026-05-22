@extends('layouts.app')
@section('title', 'Cursos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Cursos</li>
@endsection

@php
$tipos = [
    'fundamental'   => 'Fundamental',
    'medio'         => 'Médio',
    'graduacao'     => 'Graduação',
    'pos_graduacao' => 'Pós-Graduação',
    'tecnico'       => 'Técnico',
    'livre'         => 'Livre',
];
@endphp

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Cursos</h4>
    <a href="{{ route('cursos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Novo Curso
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <input type="text" name="busca" class="form-control" style="max-width:280px"
           placeholder="Buscar curso..." value="{{ request('busca') }}">
    <select name="tipo" class="form-select" style="max-width:200px">
        <option value="">Todos os tipos</option>
        @foreach($tipos as $val => $label)
        <option value="{{ $val }}" @selected(request('tipo') == $val)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','tipo']))
    <a href="{{ route('cursos.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-sp table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Duração</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cursos as $curso)
                <tr>
                    <td class="fw-semibold">{{ $curso->nome }}</td>
                    <td>
                        <span class="badge bg-secondary" style="font-size:11px">
                            {{ $tipos[$curso->tipo] ?? $curso->tipo }}
                        </span>
                    </td>
                    <td class="text-muted" style="font-size:13px">
                        {{ $curso->duracao_meses ? $curso->duracao_meses . ' meses' : '—' }}
                    </td>
                    <td>
                        @if($curso->status)
                            <span class="badge rounded-pill badge-status-ativa" style="font-size:10px">Ativo</span>
                        @else
                            <span class="badge rounded-pill badge-status-encerrada" style="font-size:10px">Inativo</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        Nenhum curso cadastrado.
                        <a href="{{ route('cursos.create') }}">Criar primeiro curso</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cursos->hasPages())
    <div class="p-3 border-top" style="border-color:#1e2d47!important">
        {{ $cursos->links() }}
    </div>
    @endif
</div>
@endsection
