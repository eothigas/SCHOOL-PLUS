@extends('layouts.app')
@section('title', 'Professores')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Professores</h4>
        <div style="font-size:13px;color:var(--text-soft)">Docentes cadastrados no sistema</div>
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
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:12px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-person-badge-fill" style="color:var(--purple);font-size:16px"></i>
                        </div>
                        <div style="font-weight:600">{{ $prof->nome }}</div>
                    </div>
                </td>
                <td style="color:var(--text-soft);font-size:13px">{{ $prof->email }}</td>
                <td>
                    @if($prof->status)
                        <span class="badge-sp badge-green">Ativo</span>
                    @else
                        <span class="badge-sp badge-muted">Inativo</span>
                    @endif
                </td>
                <td style="text-align:right">
                    <a href="{{ route('professores.edit', $prof) }}" class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('professores.destroy', $prof) }}" class="d-inline"
                          onsubmit="return confirm('Desativar professor?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;color:var(--text-soft);padding:40px">
                    Nenhum professor cadastrado.
                    <a href="{{ route('professores.create') }}" style="color:var(--purple)">Cadastrar agora</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($professores->hasPages())
    <div style="padding:16px 20px">{{ $professores->links() }}</div>
    @endif
</div>
@endsection
