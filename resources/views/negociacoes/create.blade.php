@extends('layouts.app')
@section('title', 'Nova Negociação')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            <i class="bi bi-handshake me-2" style="color:var(--purple)"></i>Nova Negociação
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">Negocie débitos vencidos e gere novas parcelas</div>
    </div>
    <a href="{{ route('negociacoes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row g-3">

    {{-- Passo 1: Selecionar matrícula --}}
    <div class="col-lg-4">
        <div class="sp-card h-100">
            <div class="section-label">1. Selecionar Aluno</div>
            <form method="GET" action="{{ route('negociacoes.create') }}">
                <div class="mb-3">
                    <label class="form-label">Matrícula ativa</label>
                    <select name="matricula_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Selecione...</option>
                        @foreach($matriculas as $mat)
                        <option value="{{ $mat->id }}" {{ request('matricula_id') == $mat->id ? 'selected' : '' }}>
                            {{ $mat->aluno->usuario->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if($matricula)
                <div style="background:var(--purple-light);border-radius:12px;padding:14px 16px;font-size:13px">
                    <div style="font-weight:700;color:var(--purple)">{{ $matricula->aluno->usuario->nome }}</div>
                    <div style="color:var(--text-soft);margin-top:2px">{{ $matricula->turma->nome ?? '—' }}</div>
                    @if($cobrancas_vencidas->isEmpty())
                    <div style="color:var(--green);margin-top:8px;font-size:12px">
                        <i class="bi bi-check-circle-fill me-1"></i>Sem débitos vencidos
                    </div>
                    @else
                    <div style="color:var(--red);margin-top:8px;font-size:12px;font-weight:600">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ $cobrancas_vencidas->count() }} débito(s) vencido(s)
                    </div>
                    @endif
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Passo 2: Negociar --}}
    <div class="col-lg-8">
        @if($matricula && $cobrancas_vencidas->isNotEmpty())
        <div class="sp-card">
            <div class="section-label">2. Configurar Acordo</div>
            <form method="POST" action="{{ route('negociacoes.store') }}" id="formNeg">
                @csrf
                <input type="hidden" name="matricula_id" value="{{ $matricula->id }}">

                {{-- Cobranças vencidas --}}
                <div style="margin-bottom:20px">
                    <div style="font-size:13px;font-weight:600;margin-bottom:10px">
                        Selecione os débitos a negociar
                    </div>
                    <div style="border:1.5px solid var(--border);border-radius:14px;overflow:hidden">
                        @foreach($cobrancas_vencidas as $cob)
                        <label style="display:flex;align-items:center;gap:14px;padding:13px 16px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s"
                               onmouseover="this.style.background='var(--purple-light)'" onmouseout="this.style.background=''">
                            <input type="checkbox" name="cobranca_ids[]" value="{{ $cob->id }}"
                                   checked class="form-check-input cob-check" style="margin:0;width:18px;height:18px"
                                   data-valor="{{ $cob->valor_original }}">
                            <div style="flex:1">
                                <div style="font-size:13px;font-weight:600">{{ $cob->descricao }}</div>
                                <div style="font-size:11px;color:var(--text-soft)">
                                    Venc. {{ $cob->data_vencimento->format('d/m/Y') }}
                                    · {{ $cob->data_vencimento->diffForHumans() }}
                                </div>
                            </div>
                            <div style="font-weight:700;color:var(--red)">
                                R$ {{ number_format($cob->valor_original, 2, ',', '.') }}
                            </div>
                        </label>
                        @endforeach
                        @php $last = $cobrancas_vencidas->last(); @endphp
                    </div>
                    <div style="display:flex;justify-content:flex-end;padding:10px 4px 0;font-size:14px;font-weight:700">
                        Total selecionado: <span id="totalSel" style="color:var(--red);margin-left:8px">
                            R$ {{ number_format($cobrancas_vencidas->sum('valor_original'), 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Condições --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Desconto (%)</label>
                        <input type="number" name="desconto_pct" id="descontoPct"
                               class="form-control" step="0.01" min="0" max="100" value="{{ old('desconto_pct', 0) }}"
                               oninput="calcResumo()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Parcelas *</label>
                        <select name="qtd_parcelas" id="qtdParcelas" class="form-select" required onchange="calcResumo()">
                            @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $p)
                            <option value="{{ $p }}" {{ old('qtd_parcelas',1) == $p ? 'selected':'' }}>{{ $p }}x</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">1ª Parcela em *</label>
                        <input type="date" name="data_primeira" class="form-control"
                               value="{{ old('data_primeira', today()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observação</label>
                        <textarea name="obs" class="form-control" rows="2"
                                  placeholder="Motivo da negociação, condições especiais...">{{ old('obs') }}</textarea>
                    </div>
                </div>

                {{-- Resumo --}}
                <div id="resumo" style="background:var(--surface2);border:1.5px solid var(--border);border-radius:14px;padding:16px;margin-top:20px">
                    <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-soft);margin-bottom:12px">Resumo do Acordo</div>
                    <div style="display:flex;gap:20px;flex-wrap:wrap">
                        <div>
                            <div style="font-size:11px;color:var(--text-soft)">Total Original</div>
                            <div style="font-size:18px;font-weight:800" id="rTotal">R$ —</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-soft)">Desconto</div>
                            <div style="font-size:18px;font-weight:800;color:var(--green)" id="rDesconto">R$ —</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-soft)">Valor Final</div>
                            <div style="font-size:18px;font-weight:800;color:var(--purple)" id="rFinal">R$ —</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-soft)">Por Parcela</div>
                            <div style="font-size:18px;font-weight:800;color:var(--blue)" id="rParcela">R$ —</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('negociacoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-handshake me-1"></i>Confirmar Negociação
                    </button>
                </div>
            </form>
        </div>

        @elseif($matricula && $cobrancas_vencidas->isEmpty())
        <div class="sp-card" style="text-align:center;padding:48px">
            <i class="bi bi-check-circle-fill" style="font-size:48px;color:var(--green);display:block;margin-bottom:16px"></i>
            <div style="font-size:15px;font-weight:700;color:var(--green)">Aluno sem débitos vencidos!</div>
            <div style="font-size:13px;color:var(--text-soft);margin-top:4px">Não há cobranças vencidas para negociar nesta matrícula.</div>
        </div>

        @else
        <div class="sp-card" style="text-align:center;padding:48px">
            <i class="bi bi-hand-index" style="font-size:48px;color:var(--border);display:block;margin-bottom:16px"></i>
            <div style="font-size:14px;color:var(--text-soft)">Selecione um aluno ao lado para ver os débitos vencidos</div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
const fmt = v => 'R$ ' + v.toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2});

function calcResumo() {
    let total = 0;
    document.querySelectorAll('.cob-check:checked').forEach(cb => total += parseFloat(cb.dataset.valor));
    document.getElementById('totalSel').textContent = fmt(total);

    const desc = parseFloat(document.getElementById('descontoPct').value) || 0;
    const parcelas = parseInt(document.getElementById('qtdParcelas').value) || 1;
    const descVal = total * desc / 100;
    const final = total - descVal;
    const parcela = final / parcelas;

    document.getElementById('rTotal').textContent   = fmt(total);
    document.getElementById('rDesconto').textContent = fmt(descVal);
    document.getElementById('rFinal').textContent   = fmt(final);
    document.getElementById('rParcela').textContent = fmt(parcela);
}

document.querySelectorAll('.cob-check').forEach(cb => cb.addEventListener('change', calcResumo));
calcResumo();
</script>
@endpush
