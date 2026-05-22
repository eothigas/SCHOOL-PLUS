@extends('layouts.app')
@section('title', 'Editar Turma')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('turmas.index') }}" class="text-decoration-none text-muted">Turmas</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Editar: {{ $turma->nome }}</h4>
    <a href="{{ route('turmas.show', $turma) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('turmas.update', $turma) }}">
    @csrf @method('PUT')
    <div class="card-sp p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-muted small">Curso *</label>
                <select name="curso_id" class="form-select">
                    @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" @selected(old('curso_id', $turma->curso_id) == $curso->id)>{{ $curso->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Período Letivo *</label>
                <select name="periodo_id" class="form-select">
                    @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}" @selected(old('periodo_id', $turma->periodo_id) == $periodo->id)>{{ $periodo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Nome da Turma *</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $turma->nome) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Turno *</label>
                <select name="turno" class="form-select">
                    @foreach(['manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite', 'integral' => 'Integral', 'ead' => 'EAD'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('turno', $turma->turno) == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Vagas *</label>
                <input type="number" name="vagas" class="form-control" value="{{ old('vagas', $turma->vagas) }}" min="1">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Sala</label>
                <input type="text" name="sala" class="form-control" value="{{ old('sala', $turma->sala) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Status</label>
                <select name="status" class="form-select">
                    @foreach(['aberta' => 'Aberta', 'em_andamento' => 'Em andamento', 'encerrada' => 'Encerrada'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $turma->status) == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar
            </button>
        </div>
    </div>
</form>
@endsection
