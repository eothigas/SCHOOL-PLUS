@extends('layouts.app')
@section('title', $periodo->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('periodos.index') }}" class="text-decoration-none text-muted">Períodos</a></li>
    <li class="breadcrumb-item active">{{ $periodo->nome }}</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">{{ $periodo->nome }}</h1>
        <div class="sp-page-hdr-sub">
            {{ $periodo->data_inicio->format('d/m/Y') }} até {{ $periodo->data_fim->format('d/m/Y') }}
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('periodos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="sp-card p-4">
            <dl class="row mb-0" style="font-size:14px; row-gap:6px">
                <dt class="col-5 text-muted fw-normal">Status</dt>
                <dd class="col-7">
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
                </dd>
                <dt class="col-5 text-muted fw-normal">Turmas</dt>
                <dd class="col-7">{{ $periodo->turmas->count() }}</dd>
            </dl>
        </div>
    </div>

    <div class="col-md-9">
        <div class="sp-card p-0">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom" >
                <h6 class="mb-0 fw-semibold">Turmas neste Período</h6>
                <a href="{{ route('turmas.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova Turma
                </a>
            </div>
                <table class="sp-table">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th>Turno</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodo->turmas as $turma)
                        <tr>
                            <td>
                                <a href="{{ route('turmas.show', $turma) }}" class="text-decoration-none fw-semibold">
                                    {{ $turma->nome }}
                                </a>
                            </td>
                            <td class="text-muted" style="font-size:13px">{{ $turma->curso->nome ?? '-' }}</td>
                            <td><span class="badge-sp badge-muted">{{ ucfirst($turma->turno) }}</span></td>
                            <td>
                                <span class="badge rounded-pill badge-status-{{ $turma->status }}" style="font-size:10px">
                                    {{ str_replace('_', ' ', $turma->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Nenhuma turma neste período.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
