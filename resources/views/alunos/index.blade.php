@extends('layouts.app')

@section('title', 'Alunos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Alunos</h4>
    <a href="{{ route('alunos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Novo Aluno
    </a>
</div>

{{-- Busca --}}
<form method="GET" class="mb-3">
    <div class="input-group" style="max-width:400px">
        <input type="text" name="busca" class="form-control" placeholder="Nome, matrícula ou e-mail..."
               value="{{ request('busca') }}">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i>
        </button>
        @if(request('busca'))
        <a href="{{ route('alunos.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-x-lg"></i>
        </a>
        @endif
    </div>
</form>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-sp table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alunos as $aluno)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $aluno->usuario->nome ?? '—' }}</div>
                    </td>
                    <td><code>{{ $aluno->matricula }}</code></td>
                    <td class="text-muted" style="font-size:13px">{{ $aluno->usuario->email ?? '—' }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $aluno->cpf ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('alunos.show', $aluno) }}" class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('alunos.edit', $aluno) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        Nenhum aluno encontrado.
                        <a href="{{ route('alunos.create') }}">Cadastrar primeiro aluno</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alunos->hasPages())
    <div class="p-3 border-top" style="border-color:#1e2d47!important">
        {{ $alunos->links() }}
    </div>
    @endif
</div>
@endsection
