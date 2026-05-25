@extends('layouts.app')

@section('title', 'Editar Aluno')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}" class="text-decoration-none text-muted">Alunos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Editar Aluno</h1>
        <div class="sp-page-hdr-sub">{{ $aluno->usuario->nome ?? '-' }}</div>
    </div>
    <a href="{{ route('alunos.show', $aluno) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('alunos.update', $aluno) }}">
    @csrf @method('PUT')
    <div class="row g-3">

        <div class="col-12">
            <div class="sp-card" style="padding:24px">
                <div class="sp-form-hdr">
                    <div class="sp-form-hdr-icon" style="background:var(--purple-light)">
                        <i class="bi bi-person" style="color:var(--purple)"></i>
                    </div>
                    <span>Dados Pessoais</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nome Completo *</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $aluno->usuario->nome) }}">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">E-mail</label>
                        <input type="email" class="form-control" value="{{ $aluno->usuario->email }}" disabled>
                        <small class="text-muted">E-mail não pode ser alterado aqui.</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">CPF</label>
                        <input type="text" name="cpf" class="form-control"
                               value="{{ old('cpf', $aluno->cpf) }}" placeholder="000.000.000-00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">Telefone</label>
                        <input type="text" name="telefone" class="form-control"
                               value="{{ old('telefone', $aluno->usuario->telefone) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control"
                               value="{{ old('data_nascimento', $aluno->data_nascimento?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="">Selecionar...</option>
                            @foreach(['M' => 'Masculino', 'F' => 'Feminino', 'outro' => 'Outro', 'nao_informado' => 'Não informado'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('sexo', $aluno->sexo) == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Nome do Pai</label>
                        <input type="text" name="nome_pai" class="form-control" value="{{ old('nome_pai', $aluno->nome_pai) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Nome da Mãe</label>
                        <input type="text" name="nome_mae" class="form-control" value="{{ old('nome_mae', $aluno->nome_mae) }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small">Endereço</label>
                        <input type="text" name="endereco" class="form-control" value="{{ old('endereco', $aluno->endereco) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Cidade</label>
                        <input type="text" name="cidade" class="form-control" value="{{ old('cidade', $aluno->cidade) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">UF</option>
                            @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                            <option value="{{ $uf }}" @selected(old('estado', $aluno->estado) == $uf)>{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">CEP</label>
                        <input type="text" name="cep" class="form-control" value="{{ old('cep', $aluno->cep) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('alunos.show', $aluno) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Salvar Alterações
            </button>
        </div>
    </div>
</form>
@endsection
