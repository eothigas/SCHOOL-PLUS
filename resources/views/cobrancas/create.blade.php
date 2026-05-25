@extends('layouts.app')
@section('title', 'Nova Cobrança')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">Nova Cobrança</h4>
        <div style="font-size:13px;color:var(--text-soft)">Cobrança avulsa para uma matrícula específica</div>
    </div>
    <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sp-card">
            <div class="section-label">Dados da Cobrança</div>
            <form method="POST" action="{{ route('cobrancas.store') }}">
                @csrf
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Matrícula / Aluno *</label>
                        <select name="matricula_id" class="form-select" required>
                            <option value="">Selecione uma matrícula...</option>
                            @foreach($matriculas as $mat)
                            <option value="{{ $mat->id }}" {{ old('matricula_id') == $mat->id ? 'selected' : '' }}>
                                {{ $mat->aluno->usuario->nome }} - {{ $mat->turma->nome ?? '-' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Descrição *</label>
                        <input type="text" name="descricao" class="form-control"
                               placeholder="Ex: Mensalidade Junho/2025, Material didático..."
                               value="{{ old('descricao') }}" required maxlength="200">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Competência</label>
                        <input type="month" name="competencia" class="form-control"
                               value="{{ old('competencia', now()->format('Y-m')) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor Original *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="valor_original" class="form-control"
                                   step="0.01" min="0.01" placeholder="0,00"
                                   value="{{ old('valor_original') }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Vencimento *</label>
                        <input type="date" name="data_vencimento" class="form-control"
                               value="{{ old('data_vencimento', now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Plano de Pagamento</label>
                        <select name="plano_id" class="form-select">
                            <option value="">Sem plano</option>
                            @foreach($planos as $plano)
                            <option value="{{ $plano->id }}" {{ old('plano_id') == $plano->id ? 'selected' : '' }}>
                                {{ $plano->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observação</label>
                        <textarea name="obs" class="form-control" rows="3"
                                  placeholder="Observações internas (opcional)...">{{ old('obs') }}</textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('cobrancas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-plus-lg me-1"></i>Criar Cobrança
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
