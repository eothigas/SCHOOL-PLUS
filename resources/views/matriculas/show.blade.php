@extends('layouts.app')
@section('title', 'Matrícula')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('matriculas.index') }}" class="text-decoration-none text-muted">Matrículas</a></li>
    <li class="breadcrumb-item active">Detalhes</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Matrícula #{{ $matricula->id }}</h4>
    <a href="{{ route('matriculas.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-sp p-4">
            <h6 class="fw-semibold mb-3 text-muted small text-uppercase">Dados da Matrícula</h6>
            <dl class="row mb-0" style="font-size:14px; row-gap:6px">
                <dt class="col-5 text-muted fw-normal">Aluno</dt>
                <dd class="col-7 fw-semibold">
                    <a href="{{ route('alunos.show', $matricula->aluno) }}" class="text-decoration-none">
                        {{ $matricula->aluno->usuario->nome ?? '—' }}
                    </a>
                </dd>

                <dt class="col-5 text-muted fw-normal">Nº Matrícula</dt>
                <dd class="col-7"><code>{{ $matricula->aluno->matricula ?? '—' }}</code></dd>

                <dt class="col-5 text-muted fw-normal">Turma</dt>
                <dd class="col-7">{{ $matricula->turma->nome ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Curso</dt>
                <dd class="col-7">{{ $matricula->turma->curso->nome ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Período</dt>
                <dd class="col-7">{{ $matricula->periodo->nome ?? '—' }}</dd>

                <dt class="col-5 text-muted fw-normal">Data</dt>
                <dd class="col-7">{{ $matricula->data_matricula?->format('d/m/Y') }}</dd>

                <dt class="col-5 text-muted fw-normal">Status</dt>
                <dd class="col-7">
                    <span class="badge rounded-pill badge-status-{{ $matricula->status }}" style="font-size:11px">
                        {{ $matricula->status }}
                    </span>
                </dd>
            </dl>

            @if($matricula->obs)
            <div class="mt-3 pt-3 border-top" style="border-color:#1e2d47!important">
                <div class="text-muted small mb-1">Observações</div>
                <p class="mb-0" style="font-size:14px">{{ $matricula->obs }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-sp p-4">
            <h6 class="fw-semibold mb-3">Alterar Status</h6>
            <form method="POST" action="{{ route('matriculas.status', $matricula) }}" class="d-flex gap-2 align-items-end">
                @csrf @method('PATCH')
                <div>
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-select" style="min-width:180px">
                        @foreach(['ativa','trancada','cancelada','concluida','transferida'] as $s)
                        <option value="{{ $s }}" @selected($matricula->status == $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label text-muted small">Observação</label>
                    <input type="text" name="obs" class="form-control" value="{{ $matricula->obs }}"
                           placeholder="Motivo da alteração...">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection
