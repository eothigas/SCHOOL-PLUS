@extends('layouts.app')
@section('title', 'Nova Disciplina')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Nova Disciplina</h4>
        <div style="font-size:13px;color:var(--text-soft)">Preencha os dados da disciplina</div>
    </div>
    <a href="{{ route('disciplinas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('disciplinas.store') }}">
    @csrf
    <div class="sp-card">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Curso *</label>
                <select name="curso_id" class="form-select @error('curso_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" @selected(old('curso_id') == $curso->id)>{{ $curso->nome }}</option>
                    @endforeach
                </select>
                @error('curso_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}" placeholder="Ex: Matemática Básica">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">Código</label>
                <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" placeholder="MAT01">
            </div>
            <div class="col-md-3">
                <label class="form-label">Carga Horária (h)</label>
                <input type="number" name="carga_horaria" class="form-control" value="{{ old('carga_horaria') }}" min="1" placeholder="80">
            </div>
            <div class="col-12">
                <label class="form-label">Ementa</label>
                <textarea name="ementa" class="form-control" rows="4" placeholder="Descrição do conteúdo programático...">{{ old('ementa') }}</textarea>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('disciplinas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Criar Disciplina
            </button>
        </div>
    </div>
</form>
@endsection
