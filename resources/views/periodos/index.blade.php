@extends('layouts.app')
@section('title', 'Períodos Letivos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Períodos Letivos</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Períodos Letivos</h1>
        <div class="sp-page-hdr-sub">Anos e semestres letivos da escola</div>
    </div>
    <a href="{{ route('periodos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Novo Período
    </a>
</div>

<form method="GET" class="mb-3 d-flex gap-2">
    <select name="status" class="form-select" style="max-width:200px">
        <option value="">Todos os status</option>
        <option value="planejamento" @selected(request('status')=='planejamento')>Planejamento</option>
        <option value="ativo" @selected(request('status')=='ativo')>Ativo</option>
        <option value="encerrado" @selected(request('status')=='encerrado')>Encerrado</option>
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request('status'))
    <a href="{{ route('periodos.index') }}" class="btn btn-outline-danger" data-no-loading><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Turmas</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($periodos as $periodo)
            @php
            $badge = match($periodo->status) {
                'ativo'        => 'badge-status-ativa',
                'planejamento' => 'badge-status-trancada',
                default        => 'badge-status-encerrada',
            };
            @endphp
            <tr>
                <td style="font-weight:600">{{ $periodo->nome }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $periodo->data_inicio->format('d/m/Y') }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $periodo->data_fim->format('d/m/Y') }}</td>
                <td style="font-size:13px;color:var(--text-soft)">{{ $periodo->turmas->count() }}</td>
                <td>
                    <span class="badge rounded-pill {{ $badge }}" style="font-size:11px">
                        {{ ucfirst($periodo->status) }}
                    </span>
                </td>
                <td style="text-align:right">
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        <a href="{{ route('periodos.show', $periodo) }}" class="icon-btn" title="Ver detalhes">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('periodos.edit', $periodo) }}" class="icon-btn" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="sp-empty">
                        <i class="bi bi-calendar3"></i>
                        <div class="sp-empty-title">Nenhum período cadastrado</div>
                        <div class="sp-empty-sub">
                            <a href="{{ route('periodos.create') }}">Criar primeiro período</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($periodos->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $periodos->links() }}
    </div>
    @endif
</div>
@endsection
