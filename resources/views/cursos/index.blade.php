@extends('layouts.app')
@section('title', 'Cursos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Cursos</li>
@endsection

@php
$tipos = [
    'fundamental'   => 'Fundamental',
    'medio'         => 'Médio',
    'graduacao'     => 'Graduação',
    'pos_graduacao' => 'Pós-Graduação',
    'tecnico'       => 'Técnico',
    'livre'         => 'Livre',
];
@endphp

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Cursos</h1>
        <div class="sp-page-hdr-sub">Gerencie os cursos oferecidos</div>
    </div>
    <a href="{{ route('cursos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Novo Curso
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <div class="sp-search" style="flex:1;min-width:200px;max-width:280px">
        <i class="bi bi-search sp-search-icon"></i>
        <input type="text" name="busca" class="form-control sp-search-input"
               placeholder="Buscar curso..." value="{{ request('busca') }}">
    </div>
    <select name="tipo" class="form-select" style="max-width:200px">
        <option value="">Todos os tipos</option>
        @foreach($tipos as $val => $label)
        <option value="{{ $val }}" @selected(request('tipo') == $val)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request()->hasAny(['busca','tipo']))
    <a href="{{ route('cursos.index') }}" class="btn btn-outline-danger" data-no-loading><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Duração</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $curso)
            <tr>
                <td style="font-weight:600">{{ $curso->nome }}</td>
                <td>
                    <span class="badge-sp badge-blue" style="font-size:11px">
                        {{ $tipos[$curso->tipo] ?? $curso->tipo }}
                    </span>
                </td>
                <td style="font-size:13px;color:var(--text-soft)">
                    {{ $curso->duracao_meses ? $curso->duracao_meses . ' meses' : '-' }}
                </td>
                <td>
                    @if($curso->status)
                        <span class="badge rounded-pill badge-status-ativa" style="font-size:11px">Ativo</span>
                    @else
                        <span class="badge rounded-pill badge-status-encerrada" style="font-size:11px">Inativo</span>
                    @endif
                </td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('cursos.show', $curso) }}" class="icon-btn" title="Ver detalhes">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('cursos.edit', $curso) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="sp-empty">
                        <i class="bi bi-journal-bookmark"></i>
                        <div class="sp-empty-title">Nenhum curso cadastrado</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('cursos.create') }}">Criar primeiro curso</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($cursos->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $cursos->links() }}
    </div>
    @endif
</div>
@endsection
