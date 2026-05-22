@extends('layouts.app')
@section('title', 'Editar Professor')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Editar: {{ $usuario->nome }}</h4>
        <div style="font-size:13px;color:var(--text-soft)">Atualize os dados do professor</div>
    </div>
    <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('professores.update', $usuario) }}">
    @csrf @method('PUT')
    <div class="sp-card" style="max-width:600px">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nome Completo *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $usuario->nome) }}">
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $usuario->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nova Senha <span style="color:var(--text-soft)">(deixe em branco p/ manter)</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 6 caracteres">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $usuario->status) == 1)>Ativo</option>
                    <option value="0" @selected(old('status', $usuario->status) == 0)>Inativo</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('professores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar
            </button>
        </div>
    </div>
</form>
@endsection
