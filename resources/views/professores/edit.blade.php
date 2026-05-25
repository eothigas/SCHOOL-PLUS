@extends('layouts.app')
@section('title', 'Editar Professor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('professores.index') }}" class="text-decoration-none text-muted">Professores</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Editar Professor</h1>
        <div class="sp-page-hdr-sub">{{ $usuario->nome }}</div>
    </div>
    <a href="{{ route('professores.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('professores.update', $usuario) }}">
    @csrf @method('PUT')
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
                               value="{{ old('nome', $usuario->nome) }}">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small">E-mail *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $usuario->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-1" style="min-width:130px">
                        <label class="form-label text-muted small">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" @selected(old('status', $usuario->status) == 1)>Ativo</option>
                            <option value="0" @selected(old('status', $usuario->status) == 0)>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="sp-card" style="padding:24px">
                <div class="sp-form-hdr">
                    <div class="sp-form-hdr-icon" style="background:var(--amber-bg)">
                        <i class="bi bi-lock-fill" style="color:var(--amber)"></i>
                    </div>
                    <span>Alterar Senha <span style="font-size:12px;font-weight:400;color:var(--text-soft)">(deixe em branco p/ manter)</span></span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nova Senha</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a senha">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar Alterações
            </button>
        </div>
    </div>
</form>
@endsection
