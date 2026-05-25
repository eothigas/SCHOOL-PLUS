@extends('layouts.app')
@section('title', 'Editar Plano')

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Editar Plano</h1>
        <div class="sp-page-hdr-sub">{{ $plano->nome }}</div>
    </div>
    <a href="{{ route('planos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sp-card">
            <div class="section-label">Dados do Plano</div>
            <form method="POST" action="{{ route('planos.update', $plano) }}">
                @csrf @method('PUT')
                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label">Nome do Plano *</label>
                        <input type="text" name="nome" class="form-control"
                               value="{{ old('nome', $plano->nome) }}" required maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-select" required>
                            @foreach(['mensal','semestral','anual','avulso'] as $t)
                            <option value="{{ $t }}" {{ old('tipo', $plano->tipo) === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor (R$) *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="valor" class="form-control"
                                   step="0.01" min="0.01"
                                   value="{{ old('valor', $plano->valor) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Dia de Vencimento *</label>
                        <input type="number" name="dia_vencimento" class="form-control"
                               min="1" max="28"
                               value="{{ old('dia_vencimento', $plano->dia_vencimento) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Curso (opcional)</label>
                        <select name="curso_id" class="form-select">
                            <option value="">Todos os cursos</option>
                            @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $plano->curso_id) == $curso->id ? 'selected' : '' }}>
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
                               step="0.01" min="0" max="100"
                               value="{{ old('desconto_pct', $plano->desconto_pct) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Multa por Atraso (%)</label>
                        <input type="number" name="multa_pct" class="form-control"
                               step="0.01" min="0" max="100"
                               value="{{ old('multa_pct', $plano->multa_pct) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Juros por Dia (%)</label>
                        <input type="number" name="juros_dia_pct" class="form-control"
                               step="0.0001" min="0"
                               value="{{ old('juros_dia_pct', $plano->juros_dia_pct) }}">
                    </div>

                    <div class="col-12"><hr class="sp-divider"></div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $plano->status) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('status', $plano->status) == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('planos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
