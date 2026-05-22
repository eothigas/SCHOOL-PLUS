@extends('layouts.app')
@section('title', $curso->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}" class="text-decoration-none text-muted">Cursos</a></li>
    <li class="breadcrumb-item active">{{ $curso->nome }}</li>
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
    <div>
        <h4 class="mb-1 fw-bold">{{ $curso->nome }}</h4>
        <small class="text-muted">{{ $tipos[$curso->tipo] ?? $curso->tipo }}
            @if($curso->duracao_meses) · {{ $curso->duracao_meses }} meses @endif
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('cursos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card-sp p-4">
            <dl class="row mb-0" style="font-size:14px; row-gap:6px">
                <dt class="col-5 text-muted fw-normal">Status</dt>
                <dd class="col-7">
                    @if($curso->status)
                        <span class="badge rounded-pill badge-status-ativa" style="font-size:10px">Ativo</span>
                    @else
                        <span class="badge rounded-pill badge-status-encerrada" style="font-size:10px">Inativo</span>
                    @endif
                </dd>
                <dt class="col-5 text-muted fw-normal">Turmas</dt>
                <dd class="col-7">{{ $curso->turmas->count() }}</dd>
                <dt class="col-5 text-muted fw-normal">Alunos</dt>
                <dd class="col-7">{{ $total_alunos }}</dd>
            </dl>
            @if($curso->descricao)
            <div class="mt-3 pt-3 border-top" style="border-color:#1e2d47!important">
                <div class="text-muted small mb-1">Descrição</div>
                <p class="mb-0" style="font-size:14px">{{ $curso->descricao }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-9">
        <div class="card-sp p-0">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom" style="border-color:#1e2d47!important">
                <h6 class="mb-0 fw-semibold">Turmas do Curso</h6>
                <a href="{{ route('turmas.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova Turma
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-sp table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Período</th>
                            <th>Turno</th>
                            <th>Vagas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curso->turmas as $turma)
                        <tr>
                            <td>
                                <a href="{{ route('turmas.show', $turma) }}" class="text-decoration-none fw-semibold">
                                    {{ $turma->nome }}
                                </a>
                            </td>
                            <td class="text-muted" style="font-size:13px">{{ $turma->periodo->nome ?? '—' }}</td>
                            <td><span class="badge bg-secondary" style="font-size:10px">{{ ucfirst($turma->turno) }}</span></td>
                            <td class="text-muted">{{ $turma->vagas }}</td>
                            <td>
                                <span class="badge rounded-pill badge-status-{{ $turma->status }}" style="font-size:10px">
                                    {{ str_replace('_', ' ', $turma->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhuma turma neste curso.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
