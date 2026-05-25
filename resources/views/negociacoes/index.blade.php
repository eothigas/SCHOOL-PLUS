@extends('layouts.app')
@section('title', 'Negociações')

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Negociações</h1>
        <div class="sp-page-hdr-sub">Acordos de débitos com alunos inadimplentes</div>
    </div>
    <a href="{{ route('negociacoes.create') }}" class="btn btn-primary">
        <i class="bi bi-handshake me-1"></i>Nova Negociação
    </a>
</div>

<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Aluno</th>
                <th>Valor Total</th>
                <th>Desconto</th>
                <th>Valor Final</th>
                <th>Parcelas</th>
                <th>Data</th>
                <th>Operador</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($negociacoes as $neg)
            @php
                $vf = $neg->valor_total * (1 - $neg->desconto_pct / 100);
            @endphp
            <tr>
                <td style="color:var(--text-soft);font-size:12px">#{{ $neg->id }}</td>
                <td style="font-weight:600">{{ $neg->matricula->aluno->usuario->nome ?? '-' }}</td>
                <td style="font-size:13px;color:var(--text-soft)">R$ {{ number_format($neg->valor_total, 2, ',', '.') }}</td>
                <td>
                    @if($neg->desconto_pct > 0)
                    <span class="badge-sp badge-green">{{ $neg->desconto_pct }}%</span>
                    @else
                    <span style="color:var(--text-soft);font-size:12px">-</span>
                    @endif
                </td>
                <td style="font-weight:700;color:var(--purple)">R$ {{ number_format($vf, 2, ',', '.') }}</td>
                <td>
                    <span class="badge-sp badge-blue">{{ $neg->qtd_parcelas }}x</span>
                    <span style="font-size:11px;color:var(--text-soft)">R$ {{ number_format($vf / $neg->qtd_parcelas, 2, ',', '.') }}</span>
                </td>
                <td style="font-size:13px">{{ \Carbon\Carbon::parse($neg->criado_em)->format('d/m/Y') }}</td>
                <td style="font-size:12px;color:var(--text-soft)">{{ $neg->usuario->nome ?? '-' }}</td>
                <td style="text-align:right">
                    <a href="{{ route('negociacoes.show', $neg) }}" class="icon-btn" title="Ver detalhes">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;color:var(--text-soft);padding:40px">
                    Nenhuma negociação registrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($negociacoes->hasPages())
    <div style="padding:16px 20px">{{ $negociacoes->links() }}</div>
    @endif
</div>
@endsection
