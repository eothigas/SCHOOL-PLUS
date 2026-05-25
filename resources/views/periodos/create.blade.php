@extends('layouts.app')
@section('title', 'Novo Período Letivo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('periodos.index') }}" class="text-decoration-none text-muted">Períodos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Novo Período Letivo</h1>
        <div class="sp-page-hdr-sub">Defina datas e status do período</div>
    </div>
    <a href="{{ route('periodos.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('periodos.store') }}">
    @csrf
    <div class="sp-card" style="padding:24px">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-muted small">Nome do Período *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}" placeholder="Ex: 2025/1, 2025 - 1º Semestre">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Data Início *</label>
                <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror"
                       value="{{ old('data_inicio') }}">
                @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Data Fim *</label>
                <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror"
                       value="{{ old('data_fim') }}">
                @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Status</label>
                <select name="status" class="form-select">
                    <option value="planejamento" @selected(old('status','planejamento')=='planejamento')>Planejamento</option>
                    <option value="ativo" @selected(old('status')=='ativo')>Ativo</option>
                    <option value="encerrado" @selected(old('status')=='encerrado')>Encerrado</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Criar Período
            </button>
        </div>
    </div>
</form>
@endsection
