@extends('layouts.app')
@section('title', 'Nova Matrícula')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('matriculas.index') }}" class="text-decoration-none text-muted">Matrículas</a></li>
    <li class="breadcrumb-item active">Nova</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Nova Matrícula</h1>
        <div class="sp-page-hdr-sub">Vincule um aluno a uma turma e período</div>
    </div>
    <a href="{{ route('matriculas.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('matriculas.store') }}">
    @csrf
    <div class="sp-card" style="padding:24px">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-muted small">Aluno *</label>
                <select name="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror">
                    <option value="">Selecionar aluno...</option>
                    @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}"
                            @selected(old('aluno_id', $aluno_selecionado?->id) == $aluno->id)>
                        {{ $aluno->usuario->nome ?? '-' }} ({{ $aluno->matricula }})
                    </option>
                    @endforeach
                </select>
                @error('aluno_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Turma *</label>
                <select name="turma_id" class="form-select @error('turma_id') is-invalid @enderror">
                    <option value="">Selecionar turma...</option>
                    @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}" @selected(old('turma_id') == $turma->id)>
                        {{ $turma->nome }} - {{ $turma->curso->nome ?? '' }}
                    </option>
                    @endforeach
                </select>
                @error('turma_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Período Letivo *</label>
                <select name="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror">
                    <option value="">Selecionar...</option>
                    @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}" @selected(old('periodo_id') == $periodo->id)>{{ $periodo->nome }}</option>
                    @endforeach
                </select>
                @error('periodo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Data da Matrícula *</label>
                <input type="date" name="data_matricula" class="form-control @error('data_matricula') is-invalid @enderror"
                       value="{{ old('data_matricula', now()->format('Y-m-d')) }}">
                @error('data_matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label text-muted small">Observações</label>
                <textarea name="obs" class="form-control" rows="2"
                          placeholder="Informações adicionais...">{{ old('obs') }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('matriculas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Realizar Matrícula
            </button>
        </div>
    </div>
</form>
@endsection
