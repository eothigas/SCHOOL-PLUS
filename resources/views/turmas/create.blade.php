@extends('layouts.app')
@section('title', 'Nova Turma')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('turmas.index') }}" class="text-decoration-none text-muted">Turmas</a></li>
    <li class="breadcrumb-item active">Nova</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Nova Turma</h1>
        <div class="sp-page-hdr-sub">Preencha os dados para criar uma turma</div>
    </div>
    <a href="{{ route('turmas.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('turmas.store') }}">
    @csrf
    <div class="sp-card" style="padding:24px">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-muted small">Curso *</label>
                <select name="curso_id" class="form-select @error('curso_id') is-invalid @enderror">
                    <option value="">Selecionar curso...</option>
                    @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" @selected(old('curso_id') == $curso->id)>{{ $curso->nome }}</option>
                    @endforeach
                </select>
                @error('curso_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Período Letivo *</label>
                <select name="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror">
                    <option value="">Selecionar período...</option>
                    @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}" @selected(old('periodo_id') == $periodo->id)>{{ $periodo->nome }}</option>
                    @endforeach
                </select>
                @error('periodo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Nome da Turma *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}" placeholder="Ex: 3º Ano A, Turma 2024/1">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Turno *</label>
                <select name="turno" class="form-select @error('turno') is-invalid @enderror">
                    <option value="">Selecionar...</option>
                    @foreach(['manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite', 'integral' => 'Integral', 'ead' => 'EAD'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('turno') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('turno')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Vagas *</label>
                <input type="number" name="vagas" class="form-control @error('vagas') is-invalid @enderror"
                       value="{{ old('vagas', 40) }}" min="1">
                @error('vagas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Sala</label>
                <input type="text" name="sala" class="form-control" value="{{ old('sala') }}" placeholder="Ex: Sala 101">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Criar Turma
            </button>
        </div>
    </div>
</form>
@endsection
