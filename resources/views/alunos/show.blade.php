@extends('layouts.app')

@section('title', $aluno->usuario->nome ?? 'Aluno')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}" class="text-decoration-none text-muted">Alunos</a></li>
    <li class="breadcrumb-item active">{{ $aluno->usuario->nome ?? '—' }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">{{ $aluno->usuario->nome ?? '—' }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('boletim.show', $aluno) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-bar-chart-line me-1"></i>Boletim
        </a>
        <a href="{{ route('alunos.edit', $aluno) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('alunos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-sp p-4">
            <h6 class="fw-semibold mb-3 text-muted small text-uppercase tracking-wider">Dados Pessoais</h6>
            <dl class="row mb-0" style="font-size:14px; row-gap:4px">
                <dt class="col-5 text-muted fw-normal">Matrícula</dt>
                <dd class="col-7"><code>{{ $aluno->matricula }}</code></dd>

                <dt class="col-5 text-muted fw-normal">E-mail</dt>
                <dd class="col-7">{{ $aluno->usuario->email ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">CPF</dt>
                <dd class="col-7">{{ $aluno->cpf ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Nascimento</dt>
                <dd class="col-7">{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Sexo</dt>
                <dd class="col-7">{{ $aluno->sexo ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Telefone</dt>
                <dd class="col-7">{{ $aluno->usuario->telefone ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-sp p-0 mb-3">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom" style="border-color:#1e2d47!important">
                <h6 class="mb-0 fw-semibold">Matrículas</h6>
                <a href="{{ route('matriculas.create', ['aluno_id' => $aluno->id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova Matrícula
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-sp table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th>Período</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aluno->matriculas as $mat)
                        <tr>
                            <td>{{ $mat->turma->nome ?? '—' }}</td>
                            <td class="text-muted" style="font-size:13px">{{ $mat->turma->curso->nome ?? '—' }}</td>
                            <td class="text-muted" style="font-size:13px">{{ $mat->periodo->nome ?? '—' }}</td>
                            <td>
                                <span class="badge rounded-pill badge-status-{{ $mat->status }}" style="font-size:10px">
                                    {{ $mat->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Nenhuma matrícula.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
