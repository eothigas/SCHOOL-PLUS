@extends('layouts.app')
@section('title', 'Novo Professor')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Novo Professor</h4>
        <div style="font-size:13px;color:var(--text-soft)">Cadastre um novo docente no sistema</div>
    </div>
    <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('professores.store') }}">
    @csrf
    <div class="sp-card" style="max-width:600px">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nome Completo *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}" placeholder="Nome do professor">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="professor@escola.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Senha *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Mínimo 6 caracteres">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar Senha *</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a senha">
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Cadastrar Professor
            </button>
        </div>
    </div>
</form>
@endsection
