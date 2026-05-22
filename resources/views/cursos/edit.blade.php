@extends('layouts.app')
@section('title', 'Editar Curso')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}" class="text-decoration-none text-muted">Cursos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Editar: {{ $curso->nome }}</h4>
    <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('cursos.update', $curso) }}">
    @csrf @method('PUT')
    <div class="card-sp p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-muted small">Nome *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $curso->nome) }}">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Tipo *</label>
                <select name="tipo" class="form-select">
                    @foreach([
                        'fundamental'   => 'Fundamental',
                        'medio'         => 'Médio',
                        'graduacao'     => 'Graduação',
                        'pos_graduacao' => 'Pós-Graduação',
                        'tecnico'       => 'Técnico',
                        'livre'         => 'Livre',
                    ] as $val => $label)
                    <option value="{{ $val }}" @selected(old('tipo', $curso->tipo) == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Duração (meses)</label>
                <input type="number" name="duracao_meses" class="form-control"
                       value="{{ old('duracao_meses', $curso->duracao_meses) }}" min="1">
            </div>
            <div class="col-md-1">
                <label class="form-label text-muted small">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected($curso->status == 1)>Ativo</option>
                    <option value="0" @selected($curso->status == 0)>Inativo</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label text-muted small">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $curso->descricao) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('cursos.show', $curso) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar
            </button>
        </div>
    </div>
</form>
@endsection
