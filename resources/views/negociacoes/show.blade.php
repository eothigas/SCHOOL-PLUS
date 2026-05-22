@extends('layouts.app')
@section('title', 'Negociação #{{ $negociacao->id }}')

@section('content')
@php
    $vf = $negociacao->valor_total * (1 - $negociacao->desconto_pct / 100);
    $vp = $negociacao->qtd_parcelas > 0 ? $vf / $negociacao->qtd_parcelas : 0;
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Negociação #{{ $negociacao->id }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            {{ $negociacao->matricula->aluno->usuario->nome ?? '—' }}
            · {{ \Carbon\Carbon::parse($negociacao->criado_em)->format('d/m/Y') }}
        </div>
    </div>
    <a href="{{ route('negociacoes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

{{-- Resumo financeiro --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div style="background:var(--surface2);border:1.5px solid var(--border);border-radius:16px;padding:18px 20px">
            <div style="font-size:11px;color:var(--text-soft);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Total Original</div>
            <div style="font-size:22px;font-weight:800;color:var(--text)">R$ {{ number_format($negociacao->valor_total, 2, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div style="background:var(--green-bg);border:1.5px solid #86efac;border-radius:16px;padding:18px 20px">
            <div style="font-size:11px;color:var(--green);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Desconto</div>
            <div style="font-size:22px;font-weight:800;color:var(--green)">{{ $negociacao->desconto_pct }}%</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div style="background:var(--purple-light);border:1.5px solid var(--purple-mid);border-radius:16px;padding:18px 20px">
            <div style="font-size:11px;color:var(--purple);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Valor Final</div>
            <div style="font-size:22px;font-weight:800;color:var(--purple)">R$ {{ number_format($vf, 2, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div style="background:var(--blue-bg);border:1.5px solid #93c5fd;border-radius:16px;padding:18px 20px">
            <div style="font-size:11px;color:var(--blue);font-weight:700;text-transform:uppercase;letter-spacing:.05em">{{ $negociacao->qtd_parcelas }}x de</div>
            <div style="font-size:22px;font-weight:800;color:var(--blue)">R$ {{ number_format($vp, 2, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Info --}}
    <div class="col-md-4">
        <div class="sp-card">
            <div class="section-label">Detalhes</div>
            <dl class="row mb-0" style="font-size:13px;row-gap:8px">
                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Aluno</dt>
                <dd class="col-7 fw-semibold">{{ $negociacao->matricula->aluno->usuario->nome ?? '—' }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Operador</dt>
                <dd class="col-7">{{ $negociacao->usuario->nome ?? '—' }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Registrado em</dt>
                <dd class="col-7">{{ \Carbon\Carbon::parse($negociacao->criado_em)->format('d/m/Y H:i') }}</dd>

                <dt class="col-5" style="color:var(--text-soft);font-weight:500">Parcelas</dt>
                <dd class="col-7">{{ $negociacao->qtd_parcelas }}x</dd>
            </dl>

            @if($negociacao->obs)
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border)">
                <div style="font-size:11px;color:var(--text-soft);margin-bottom:4px">Observação</div>
                <div style="font-size:13px">{{ $negociacao->obs }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Parcelas geradas --}}
    <div class="col-md-8">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border)">
                <div style="font-size:14px;font-weight:700">Parcelas Geradas</div>
            </div>
            <table class="sp-table">
                <thead>
                    <tr><th>#</th><th>Descrição</th><th>Vencimento</th><th>Valor</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($cobrancas_geradas as $idx => $cob)
                    @php
                        $sr = $cob->status_real;
                        $badge = match($sr) {
                            'paga'      => 'badge-green',
                            'vencida'   => 'badge-red',
                            'cancelada' => 'badge-muted',
                            default     => 'badge-amber',
                        };
                        $label = match($sr) {
                            'paga'      => 'Paga',
                            'vencida'   => 'Vencida',
                            'cancelada' => 'Cancelada',
                            default     => 'A Vencer',
                        };
                    @endphp
                    <tr>
                        <td style="color:var(--text-soft);font-size:12px">{{ $idx + 1 }}</td>
                        <td style="font-size:13px">{{ $cob->descricao }}</td>
                        <td style="font-size:13px;{{ $sr==='vencida' ? 'color:var(--red);font-weight:700' : '' }}">
                            {{ $cob->data_vencimento->format('d/m/Y') }}
                        </td>
                        <td style="font-weight:700">R$ {{ number_format($cob->valor_original, 2, ',', '.') }}</td>
                        <td><span class="badge-sp {{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <a href="{{ route('cobrancas.show', $cob) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-soft);padding:24px">Nenhuma parcela encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
