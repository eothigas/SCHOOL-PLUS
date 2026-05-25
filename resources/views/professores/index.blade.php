@extends('layouts.app')
@section('title', 'Professores')

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Professores</h1>
        <div class="sp-page-hdr-sub">Docentes cadastrados no sistema</div>
    </div>
    <a href="{{ route('professores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Professor
    </a>
</div>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Professor</th>
                <th>E-mail</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($professores as $prof)
            @php
                $parts  = explode(' ', $prof->nome);
                $initials = mb_strtoupper(mb_substr($parts[0],0,1) . (isset($parts[1]) ? mb_substr($parts[1],0,1) : ''));
                $colors = ['','av-green','av-amber','av-blue','av-red'];
                $color  = $colors[$prof->id % 5];
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:11px">
                        <div class="idx-avatar {{ $color }}">{{ $initials }}</div>
                        <div style="font-weight:600;font-size:14px">{{ $prof->nome }}</div>
                    </div>
                </td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $prof->email }}</td>
                <td>
                    @if($prof->status)
                        <span class="badge-sp badge-green">Ativo</span>
                    @else
                        <span class="badge-sp badge-muted">Inativo</span>
                    @endif
                </td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('professores.edit', $prof) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('professores.destroy', $prof) }}" class="d-inline"
                              onsubmit="return confirm('Desativar professor?')">
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
                <td colspan="4">
                    <div class="sp-empty">
                        <i class="bi bi-person-badge"></i>
                        <div class="sp-empty-title">Nenhum professor cadastrado</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('professores.create') }}">Cadastrar agora</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($professores->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $professores->links() }}
    </div>
    @endif
</div>
@endsection
