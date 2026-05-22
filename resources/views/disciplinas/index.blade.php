@extends('layouts.app')
@section('title', 'Disciplinas')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Disciplinas</h4>
        <div style="font-size:13px;color:var(--text-soft)">Gerencie as disciplinas dos cursos</div>
    </div>
    <a href="{{ route('disciplinas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nova Disciplina
    </a>
</div>

{{-- Filtros --}}
<div class="sp-card mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Buscar</label>
            <input type="text" name="busca" class="form-control" placeholder="Nome da disciplina..." value="{{ request('busca') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Curso</label>
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
            <a href="{{ route('disciplinas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

{{-- Tabela --}}
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
                <td style="font-size:13px;color:var(--text-soft)">{{ $disc->curso->nome ?? '—' }}</td>
                <td>
                    @if($disc->carga_horaria)
                        <span style="font-size:13px">{{ $disc->carga_horaria }}h</span>
                    @else
                        <span style="color:var(--text-soft)">—</span>
                    @endif
                </td>
                <td style="text-align:right">
                    <a href="{{ route('disciplinas.edit', $disc) }}" class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('disciplinas.destroy', $disc) }}" class="d-inline"
                          onsubmit="return confirm('Desativar disciplina?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:var(--text-soft);padding:40px">
                    Nenhuma disciplina encontrada.
                    <a href="{{ route('disciplinas.create') }}" style="color:var(--purple)">Criar agora</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($disciplinas->hasPages())
    <div style="padding:16px 20px">
        {{ $disciplinas->links() }}
    </div>
    @endif
</div>
@endsection
