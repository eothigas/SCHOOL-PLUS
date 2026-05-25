@extends('layouts.app')
@section('title', 'Disciplinas')

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Disciplinas</h1>
        <div class="sp-page-hdr-sub">Gerencie as disciplinas dos cursos</div>
    </div>
    <a href="{{ route('disciplinas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nova Disciplina
    </a>
</div>

<div class="sp-card" style="padding:20px;margin-bottom:16px">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label text-muted small">Buscar</label>
            <input type="text" name="busca" class="form-control" placeholder="Nome da disciplina..." value="{{ request('busca') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label text-muted small">Curso</label>
            <select name="curso_id" class="form-select">
                <option value="">Todos os cursos</option>
                @foreach($cursos as $curso)
                <option value="{{ $curso->id }}" @selected(request('curso_id') == $curso->id)>{{ $curso->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="bi bi-search me-1"></i>Filtrar
            </button>
            <a href="{{ route('disciplinas.index') }}" class="btn btn-outline-secondary" data-no-loading>
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Disciplina</th>
                <th>Código</th>
                <th>Curso</th>
                <th>Carga Horária</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($disciplinas as $disc)
            <tr>
                <td style="font-weight:600">{{ $disc->nome }}</td>
                <td>
                    @if($disc->codigo)
                        <span class="badge-sp badge-purple">{{ $disc->codigo }}</span>
                    @else
                        <span style="color:var(--text-soft)">—</span>
                    @endif
                </td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $disc->curso->nome ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">
                    {{ $disc->carga_horaria ? $disc->carga_horaria . 'h' : '—' }}
                </td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('disciplinas.edit', $disc) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('disciplinas.destroy', $disc) }}" class="d-inline"
                              onsubmit="return confirm('Desativar disciplina?')">
                            @csrf @method('DELETE')
                            <button class="icon-btn danger" type="submit" title="Desativar" data-no-spin>
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="sp-empty">
                        <i class="bi bi-book"></i>
                        <div class="sp-empty-title">Nenhuma disciplina encontrada</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('disciplinas.create') }}">Criar agora</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($disciplinas->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $disciplinas->links() }}
    </div>
    @endif
</div>
@endsection
