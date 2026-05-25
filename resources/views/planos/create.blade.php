@extends('layouts.app')
@section('title', 'Novo Plano de Pagamento')

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Novo Plano de Pagamento</h1>
        <div class="sp-page-hdr-sub">Configure valor, vencimento e condições de atraso</div>
    </div>
    <a href="{{ route('planos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sp-card">
            <div class="section-label">Dados do Plano</div>
            <form method="POST" action="{{ route('planos.store') }}">
                @csrf
                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label">Nome do Plano *</label>
                        <input type="text" name="nome" class="form-control"
                               placeholder="Ex: Mensalidade Regular, Plano Anual..."
                               value="{{ old('nome') }}" required maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-select" required>
                            <option value="mensal"    {{ old('tipo','mensal') === 'mensal'    ? 'selected' : '' }}>Mensal</option>
                            <option value="semestral" {{ old('tipo') === 'semestral' ? 'selected' : '' }}>Semestral</option>
                            <option value="anual"     {{ old('tipo') === 'anual'     ? 'selected' : '' }}>Anual</option>
                            <option value="avulso"    {{ old('tipo') === 'avulso'    ? 'selected' : '' }}>Avulso</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor (R$) *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="valor" class="form-control"
                                   step="0.01" min="0.01" placeholder="0,00"
                                   value="{{ old('valor') }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Dia de Vencimento *</label>
                        <input type="number" name="dia_vencimento" class="form-control"
                               min="1" max="28" placeholder="Ex: 10"
                               value="{{ old('dia_vencimento', 10) }}" required>
                        <div style="font-size:11px;color:var(--text-soft);margin-top:4px">Entre 1 e 28</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Curso (opcional)</label>
                        <select name="curso_id" class="form-select">
                            <option value="">Todos os cursos</option>
                            @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12"><hr class="sp-divider"></div>
                    <div class="col-12">
                        <div style="font-size:12px;font-weight:700;color:var(--text-soft);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">
                            Condições de Atraso
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Desconto Pontualidade (%)</label>
                        <input type="number" name="desconto_pct" class="form-control"
                               step="0.01" min="0" max="100" placeholder="0"
                               value="{{ old('desconto_pct', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Multa por Atraso (%)</label>
                        <input type="number" name="multa_pct" class="form-control"
                               step="0.01" min="0" max="100" placeholder="2"
                               value="{{ old('multa_pct', 2) }}">
                        <div style="font-size:11px;color:var(--text-soft);margin-top:4px">Aplicada uma vez no atraso</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Juros por Dia (%)</label>
                        <input type="number" name="juros_dia_pct" class="form-control"
                               step="0.0001" min="0" placeholder="0.0333"
                               value="{{ old('juros_dia_pct', 0.0333) }}">
                        <div style="font-size:11px;color:var(--text-soft);margin-top:4px">1% ao mês ≈ 0,0333%/dia</div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('planos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Criar Plano
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
