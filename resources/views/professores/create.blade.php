@extends('layouts.app')
@section('title', 'Novo Professor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('professores.index') }}" class="text-decoration-none text-muted">Professores</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Novo Professor</h1>
        <div class="sp-page-hdr-sub">Cadastre um novo docente no sistema</div>
    </div>
    <a href="{{ route('professores.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('professores.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-12">
            <div class="sp-card" style="padding:24px">
                <div class="sp-form-hdr">
                    <div class="sp-form-hdr-icon" style="background:var(--purple-light)">
                        <i class="bi bi-person-badge" style="color:var(--purple)"></i>
                    </div>
                    <span>Identificação</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome Completo *</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="Nome do professor">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">E-mail *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="professor@escola.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="sp-card" style="padding:24px">
                <div class="sp-form-hdr">
                    <div class="sp-form-hdr-icon" style="background:var(--green-bg)">
                        <i class="bi bi-lock-fill" style="color:var(--green)"></i>
                    </div>
                    <span>Acesso ao Sistema</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Senha *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Confirmar Senha *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a senha">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Cadastrar Professor
            </button>
        </div>
    </div>
</form>
@endsection
