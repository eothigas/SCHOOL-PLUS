@extends('layouts.app')
@section('title', 'Cobranças')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">Cobranças</h4>
        <div style="font-size:13px;color:var(--text-soft)">Gerencie todas as cobranças do sistema</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('cobrancas.gerar') }}" class="btn btn-outline-primary">
            <i class="bi bi-lightning-fill me-1"></i>Gerar Lote
        </a>
        <a href="{{ route('cobrancas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nova Cobrança
        </a>
    </div>
</div>

{{-- Totais rápidos --}}
<div class="row g-2 mb-4">
    <div class="col-4">
        <div style="background:var(--blue-bg);border-radius:14px;padding:14px 18px">
            <div style="font-size:11px;color:var(--blue);font-weight:700;text-transform:uppercase;letter-spacing:.05em">A Receber</div>
            <div style="font-size:20px;font-weight:800;color:var(--blue)">R$ {{ number_format($totais['aberta'], 2, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-4">
        <div style="background:var(--red-bg);border-radius:14px;padding:14px 18px">
            <div style="font-size:11px;color:var(--red);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Vencidas</div>
            <div style="font-size:20px;font-weight:800;color:var(--red)">R$ {{ number_format($totais['vencida'], 2, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-4">
        <div style="background:var(--green-bg);border-radius:14px;padding:14px 18px">
            <div style="font-size:11px;color:var(--green);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Pago no Mês</div>
            <div style="font-size:20px;font-weight:800;color:var(--green)">R$ {{ number_format($totais['paga_mes'], 2, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="sp-card mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Buscar aluno / descrição</label>
            <input type="text" name="busca" class="form-control" placeholder="Nome ou descrição..." value="{{ request('busca') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="a_vencer"  @selected(request('status')=='a_vencer')>A Vencer</option>
                <option value="vencida"   @selected(request('status')=='vencida')>Vencidas</option>
                <option value="paga"      @selected(request('status')=='paga')>Pagas</option>
                <option value="cancelada" @selected(request('status')=='cancelada')>Canceladas</option>
                <option value="negociada" @selected(request('status')=='negociada')>Negociadas</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Competência</label>
            <input type="month" name="competencia" class="form-control" value="{{ request('competencia') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i>Filtrar</button>
            <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

{{-- Tabela --}}
<div class="sp-card" style="padding:0">
    <table class="sp-table">
        <thead>
            <tr><th>Aluno</th><th>Descrição</th><th>Vencimento</th><th>Valor</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($cobrancas as $cob)
            @php
                $sr = $cob->status_real;
                $badge = match($sr) {
                    'paga'      => 'badge-green',
                    'vencida'   => 'badge-red',
                    'cancelada' => 'badge-muted',
                    'negociada' => 'badge-blue',
                    default     => 'badge-amber',
                };
                $label = match($sr) {
                    'paga'      => 'Paga',
                    'vencida'   => 'Vencida',
                    'cancelada' => 'Cancelada',
                    'negociada' => 'Negociada',
                    default     => 'A Vencer',
                };
            @endphp
            <tr>
                <td style="font-weight:600">{{ $cob->matricula->aluno->usuario->nome ?? '—' }}</td>
                <td style="font-size:13px;color:var(--text-soft);max-width:200px">{{ $cob->descricao }}</td>
                <td style="font-size:13px;color:{{ $sr==='vencida' ? 'var(--red)' : 'var(--text)' }};font-weight:{{ $sr==='vencida' ? '700' : '400' }}">
                    {{ $cob->data_vencimento->format('d/m/Y') }}
                    @if($sr==='vencida')
                    <div style="font-size:10px">{{ $cob->data_vencimento->diffForHumans() }}</div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:700;font-size:14px">R$ {{ number_format($cob->valor_original, 2, ',', '.') }}</div>
                    @if($cob->valor_desconto > 0)
                    <div style="font-size:10px;color:var(--green)">-R$ {{ number_format($cob->valor_desconto, 2, ',', '.') }}</div>
                    @endif
                </td>
                <td><span class="badge-sp {{ $badge }}">{{ $label }}</span></td>
                <td>
                    <a href="{{ route('cobrancas.show', $cob) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--text-soft);padding:40px">Nenhuma cobrança encontrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($cobrancas->hasPages())
    <div style="padding:16px 20px">{{ $cobrancas->links() }}</div>
    @endif
</div>
@endsection
