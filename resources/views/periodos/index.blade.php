@extends('layouts.app')
@section('title', 'Períodos Letivos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Períodos Letivos</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Períodos Letivos</h4>
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
    <a href="{{ route('periodos.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
    @endif
</form>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-sp table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Turmas</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodos as $periodo)
                <tr>
                    <td class="fw-semibold">{{ $periodo->nome }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $periodo->data_inicio->format('d/m/Y') }}</td>
                    <td class="text-muted" style="font-size:13px">{{ $periodo->data_fim->format('d/m/Y') }}</td>
                    <td class="text-muted">{{ $periodo->turmas->count() }}</td>
                    <td>
                        @php
                        $badge = match($periodo->status) {
                            'ativo'        => 'badge-status-ativa',
                            'planejamento' => 'badge-status-trancada',
                            default        => 'badge-status-encerrada',
                        };
                        @endphp
                        <span class="badge rounded-pill {{ $badge }}" style="font-size:10px">
                            {{ ucfirst($periodo->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('periodos.show', $periodo) }}" class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        Nenhum período cadastrado.
                        <a href="{{ route('periodos.create') }}">Criar primeiro período</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($periodos->hasPages())
    <div class="p-3 border-top" style="border-color:#1e2d47!important">
        {{ $periodos->links() }}
    </div>
    @endif
</div>
@endsection
