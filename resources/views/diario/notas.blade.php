@extends('layouts.app')
@section('title', 'Lançar Notas')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Notas - {{ $avaliacao->nome }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            {{ $td->disciplina->nome }} &middot; {{ $td->turma->nome }}
            &middot; Máximo: <strong>{{ number_format($avaliacao->nota_maxima, 1) }}</strong>
        </div>
    </div>
    <a href="{{ route('diario.index', $td->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar ao Diário
    </a>
</div>

<div class="sp-card mb-4" style="background:var(--purple-light);border-color:rgba(124,58,237,.2)">
    <div class="row g-3 text-center">
        <div class="col-4">
            <div style="font-size:11px;color:var(--purple);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Tipo</div>
            <div style="font-size:16px;font-weight:800;color:var(--purple)">{{ ucfirst($avaliacao->tipo) }}</div>
        </div>
        <div class="col-4">
            <div style="font-size:11px;color:var(--purple);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Nota Máxima</div>
            <div style="font-size:16px;font-weight:800;color:var(--purple)">{{ number_format($avaliacao->nota_maxima, 1) }}</div>
        </div>
        <div class="col-4">
            <div style="font-size:11px;color:var(--purple);font-weight:700;text-transform:uppercase;letter-spacing:.05em">Data</div>
            <div style="font-size:16px;font-weight:800;color:var(--purple)">
                {{ $avaliacao->data_aplicacao ? $avaliacao->data_aplicacao->format('d/m/Y') : '-' }}
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('diario.notas.salvar', [$td->id, $avaliacao->id]) }}">
    @csrf

    <div class="sp-card" style="padding:0">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border)">
            <div style="font-size:14px;font-weight:700">Lançar Notas</div>
            <div style="font-size:11px;color:var(--text-soft)">{{ $matriculas->count() }} aluno(s)</div>
        </div>

        <table class="sp-table">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th style="width:160px">Nota (0–{{ number_format($avaliacao->nota_maxima, 0) }})</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matriculas as $mat)
                @php $nota_val = $notas_existentes[$mat->id] ?? ''; @endphp
                <tr>
                    <td style="font-weight:600">{{ $mat->aluno->usuario->nome ?? '-' }}</td>
                    <td>
                        <input type="number" name="notas[{{ $mat->id }}]"
                               class="form-control nota-input" style="width:120px"
                               value="{{ $nota_val }}"
                               min="0" max="{{ $avaliacao->nota_maxima }}" step="0.1"
                               placeholder="-">
                    </td>
                    <td>
                        <input type="text" name="obs[{{ $mat->id }}]"
                               class="form-control" style="font-size:13px"
                               placeholder="Obs opcional...">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:16px 20px;display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary px-5">
                <i class="bi bi-check-lg me-2"></i>Salvar Notas
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.querySelectorAll('.nota-input').forEach(input => {
    input.addEventListener('change', function() {
        const max = parseFloat('{{ $avaliacao->nota_maxima }}');
        const val = parseFloat(this.value);
        if (!isNaN(val) && val > max) this.value = max;
        if (!isNaN(val) && val < 0)   this.value = 0;
    });
});
</script>
@endpush
@endsection
