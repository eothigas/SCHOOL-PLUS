@extends('layouts.app')

@section('title', 'Alunos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Alunos</h1>
        <div class="sp-page-hdr-sub">Gerencie os alunos matriculados</div>
    </div>
    <a href="{{ route('alunos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Novo Aluno
    </a>
</div>

{{-- Busca --}}
<form method="GET" class="mb-3">
    <div class="sp-search" style="max-width:400px">
        <i class="bi bi-search sp-search-icon"></i>
        <input type="text" name="busca" class="form-control sp-search-input"
               placeholder="Nome, matrícula ou e-mail..." value="{{ request('busca') }}">
        @if(request('busca'))
        <a href="{{ route('alunos.index') }}" class="sp-search-clear" data-no-loading>
            <i class="bi bi-x-lg"></i>
        </a>
        @endif
    </div>
</form>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Matrícula</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($alunos as $aluno)
            @php
                $nome   = $aluno->usuario->nome ?? '';
                $parts  = explode(' ', $nome);
                $initials = mb_strtoupper(mb_substr($parts[0],0,1) . (isset($parts[1]) ? mb_substr($parts[1],0,1) : ''));
                $colors = ['','av-green','av-amber','av-blue','av-red'];
                $color  = $colors[$aluno->id % 5];
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:11px">
                        <div class="idx-avatar {{ $color }}">{{ $initials }}</div>
                        <div style="font-weight:600;font-size:14px">{{ $nome ?: '-' }}</div>
                    </div>
                </td>
                <td><code style="font-size:12px;color:var(--purple)">{{ $aluno->matricula }}</code></td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $aluno->usuario->email ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $aluno->cpf ?? '-' }}</td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('alunos.show', $aluno) }}" class="icon-btn" title="Ver detalhes">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('alunos.edit', $aluno) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="sp-empty">
                        <i class="bi bi-people"></i>
                        <div class="sp-empty-title">Nenhum aluno encontrado</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('alunos.create') }}">Cadastrar primeiro aluno</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($alunos->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $alunos->links() }}
    </div>
    @endif
</div>
@endsection
