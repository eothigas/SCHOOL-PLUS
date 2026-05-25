@extends('layouts.app')
@section('title', 'Editar Período')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('periodos.index') }}" class="text-decoration-none text-muted">Períodos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Editar Período</h1>
        <div class="sp-page-hdr-sub">{{ $periodo->nome }}</div>
    </div>
    <a href="{{ route('periodos.show', $periodo) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('periodos.update', $periodo) }}">
    @csrf @method('PUT')
    <div class="sp-card" style="padding:24px">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-muted small">Nome *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $periodo->nome) }}">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Data Início *</label>
                <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror"
                       value="{{ old('data_inicio', $periodo->data_inicio->format('Y-m-d')) }}">
                @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Data Fim *</label>
                <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror"
                       value="{{ old('data_fim', $periodo->data_fim->format('Y-m-d')) }}">
                @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Status</label>
                <select name="status" class="form-select">
                    @foreach(['planejamento' => 'Planejamento', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $periodo->status) == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('periodos.show', $periodo) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar
            </button>
        </div>
    </div>
</form>
@endsection
