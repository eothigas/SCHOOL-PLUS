@extends('layouts.app')
@section('title', 'Gerar Mensalidades em Lote')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            <i class="bi bi-lightning-fill me-2" style="color:var(--amber)"></i>Gerar Mensalidades em Lote
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">Gera cobranças mensais automáticas para uma matrícula</div>
    </div>
    <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="sp-card mb-3" style="background:var(--amber-bg);border-color:#fcd34d">
            <div style="display:flex;gap:12px;align-items:flex-start">
                <i class="bi bi-info-circle-fill" style="color:var(--amber);font-size:18px;flex-shrink:0;margin-top:2px"></i>
                <div style="font-size:13px;color:#92400e">
                    <strong>Como funciona:</strong> O sistema gera cobranças mensais para a matrícula selecionada, usando o valor e o dia de vencimento do plano. Cobranças com mesma competência + plano já existentes são ignoradas automaticamente (sem duplicatas).
                </div>
            </div>
        </div>

        <div class="sp-card">
            <div class="section-label">Configurar Geração</div>
            <form method="POST" action="{{ route('cobrancas.gerar.store') }}">
                @csrf
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Matrícula / Aluno *</label>
                        <select name="matricula_id" class="form-select" required>
                            <option value="">Selecione uma matrícula...</option>
                            @foreach($matriculas as $mat)
                            <option value="{{ $mat->id }}" {{ old('matricula_id') == $mat->id ? 'selected' : '' }}>
                                {{ $mat->aluno->usuario->nome }} — {{ $mat->turma->nome ?? '—' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Plano de Pagamento *</label>
                        <select name="plano_id" class="form-select" required id="planoSelect">
                            <option value="">Selecione um plano...</option>
                            @foreach($planos as $plano)
                            <option value="{{ $plano->id }}"
                                    data-valor="{{ $plano->valor }}"
                                    data-dia="{{ $plano->dia_vencimento }}"
                                    {{ old('plano_id') == $plano->id ? 'selected' : '' }}>
                                {{ $plano->nome }} — R$ {{ number_format($plano->valor, 2, ',', '.') }} / dia {{ $plano->dia_vencimento }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="planoInfo" style="display:none" class="col-12">
                        <div style="background:var(--purple-light);border-radius:12px;padding:14px 18px;font-size:13px;color:var(--purple)">
                            <strong>Plano selecionado:</strong>
                            Valor R$ <span id="piValor">—</span> &nbsp;·&nbsp;
                            Vencimento dia <span id="piDia">—</span> de cada mês
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mês inicial *</label>
                        <input type="date" name="data_inicio" class="form-control"
                               value="{{ old('data_inicio', now()->startOfMonth()->format('Y-m-d')) }}" required>
                        <div style="font-size:11px;color:var(--text-soft);margin-top:4px">O vencimento será ajustado para o dia do plano</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Quantidade de meses *</label>
                        <select name="qtd_meses" class="form-select" required>
                            @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $m)
                            <option value="{{ $m }}" {{ old('qtd_meses', 1) == $m ? 'selected' : '' }}>
                                {{ $m }} {{ $m == 1 ? 'mês' : 'meses' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-lightning-fill me-1"></i>Gerar Cobranças
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('planoSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const info = document.getElementById('planoInfo');
    if (opt.value) {
        document.getElementById('piValor').textContent = parseFloat(opt.dataset.valor).toLocaleString('pt-BR', {minimumFractionDigits:2});
        document.getElementById('piDia').textContent   = opt.dataset.dia;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
});
</script>
@endpush
