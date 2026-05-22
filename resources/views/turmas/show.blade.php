@extends('layouts.app')
@section('title', $turma->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('turmas.index') }}" class="text-decoration-none text-muted">Turmas</a></li>
    <li class="breadcrumb-item active">{{ $turma->nome }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1 fw-bold">{{ $turma->nome }}</h4>
        <small class="text-muted">{{ $turma->curso->nome ?? '—' }} · {{ $turma->periodo->nome ?? '—' }} · {{ ucfirst($turma->turno) }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('turmas.disciplinas', $turma) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-journal-richtext me-1"></i>Diário
        </a>
        <a href="{{ route('turmas.edit', $turma) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('turmas.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card-sp p-4">
            <div class="mb-3">
                <div class="text-muted small">Status</div>
                <span class="badge rounded-pill badge-status-{{ $turma->status }}">
                    {{ str_replace('_', ' ', $turma->status) }}
                </span>
            </div>
            <dl style="font-size:14px; row-gap:4px" class="row mb-0">
                <dt class="col-5 text-muted fw-normal">Vagas</dt>
                <dd class="col-7">{{ $turma->vagas }}</dd>
                <dt class="col-5 text-muted fw-normal">Sala</dt>
                <dd class="col-7">{{ $turma->sala ?? '—' }}</dd>
                <dt class="col-5 text-muted fw-normal">Alunos</dt>
                <dd class="col-7">{{ $turma->matriculas->count() }}</dd>
            </dl>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card-sp p-0">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom" style="border-color:#1e2d47!important">
                <h6 class="mb-0 fw-semibold">Alunos Matriculados</h6>
                <a href="{{ route('matriculas.create', ['turma_id' => $turma->id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Matricular Aluno
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-sp table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($turma->matriculas as $mat)
                        <tr>
                            <td>
                                <a href="{{ route('alunos.show', $mat->aluno) }}" class="text-decoration-none fw-semibold">
                                    {{ $mat->aluno->usuario->nome ?? '—' }}
                                </a>
                            </td>
                            <td><code>{{ $mat->aluno->matricula ?? '—' }}</code></td>
                            <td>
                                <span class="badge rounded-pill badge-status-{{ $mat->status }}" style="font-size:10px">
                                    {{ $mat->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Nenhum aluno matriculado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
