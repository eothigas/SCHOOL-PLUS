@extends('layouts.app')

@section('title', 'Novo Aluno')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}" class="text-decoration-none text-muted">Alunos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Novo Aluno</h4>
    <a href="{{ route('alunos.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('alunos.store') }}">
    @csrf
    <div class="row g-3">

        {{-- Dados de Acesso --}}
        <div class="col-12">
            <div class="card-sp p-4">
                <h6 class="fw-semibold mb-3 pb-2 border-bottom" style="border-color:#1e2d47!important">
                    <i class="bi bi-person-lock me-2 text-primary"></i>Dados de Acesso
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nome Completo *</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="Nome do aluno">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">E-mail *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="email@escola.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Senha *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Dados Acadêmicos --}}
        <div class="col-12">
            <div class="card-sp p-4">
                <h6 class="fw-semibold mb-3 pb-2 border-bottom" style="border-color:#1e2d47!important">
                    <i class="bi bi-mortarboard me-2 text-success"></i>Dados Acadêmicos
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Nº de Matrícula *</label>
                        <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror"
                               value="{{ old('matricula') }}" placeholder="2024001">
                        @error('matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control"
                               value="{{ old('data_nascimento') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="">Selecionar...</option>
                            <option value="M" @selected(old('sexo')=='M')>Masculino</option>
                            <option value="F" @selected(old('sexo')=='F')>Feminino</option>
                            <option value="outro" @selected(old('sexo')=='outro')>Outro</option>
                            <option value="nao_informado" @selected(old('sexo')=='nao_informado')>Não informado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">CPF</label>
                        <input type="text" name="cpf" class="form-control"
                               value="{{ old('cpf') }}" placeholder="000.000.000-00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Telefone</label>
                        <input type="text" name="telefone" class="form-control"
                               value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                    </div>
                </div>
            </div>
        </div>

        {{-- Filiação --}}
        <div class="col-12">
            <div class="card-sp p-4">
                <h6 class="fw-semibold mb-3 pb-2 border-bottom" style="border-color:#1e2d47!important">
                    <i class="bi bi-people me-2 text-warning"></i>Filiação & Endereço
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome do Pai</label>
                        <input type="text" name="nome_pai" class="form-control" value="{{ old('nome_pai') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome da Mãe</label>
                        <input type="text" name="nome_mae" class="form-control" value="{{ old('nome_mae') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small">Endereço</label>
                        <input type="text" name="endereco" class="form-control" value="{{ old('endereco') }}"
                               placeholder="Rua, número, complemento">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Cidade</label>
                        <input type="text" name="cidade" class="form-control" value="{{ old('cidade') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">UF</option>
                            @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                            <option value="{{ $uf }}" @selected(old('estado')==$uf)>{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">CEP</label>
                        <input type="text" name="cep" class="form-control" value="{{ old('cep') }}"
                               placeholder="00000-000">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('alunos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar Aluno
            </button>
        </div>
    </div>
</form>
@endsection
