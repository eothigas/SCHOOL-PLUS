@extends('layouts.app')
@section('title', 'Frequência da Aula')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Frequência - {{ $aula->data_aula->format('d/m/Y') }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            {{ $td->disciplina->nome }} &middot; {{ $td->turma->nome }}
        </div>
    </div>
    <a href="{{ route('diario.index', $td->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar ao Diário
    </a>
</div>

@if($aula->conteudo)
<div class="sp-card mb-4" style="background:var(--purple-light);border-color:rgba(124,58,237,.2)">
    <div style="font-size:12px;color:var(--purple);font-weight:700;margin-bottom:4px">
        <i class="bi bi-journal-text me-1"></i>CONTEÚDO DA AULA
    </div>
    <div style="font-size:14px;color:var(--text)">{{ $aula->conteudo }}</div>
</div>
@endif

<form method="POST" action="{{ route('diario.frequencia', [$td->id, $aula->id]) }}">
    @csrf @method('PATCH')

    <div class="sp-card" style="padding:0">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:14px;font-weight:700">Lista de Presença</div>
            <div style="display:flex;gap:8px">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll(true)">
                    <i class="bi bi-check-all me-1"></i>Todos presentes
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll(false)">
                    <i class="bi bi-x-circle me-1"></i>Todos ausentes
                </button>
            </div>
        </div>

        @php
            $presentes_count = $aula->frequencias->where('presente', 1)->count();
            $total = $aula->frequencias->count();
        @endphp

        @if($total > 0)
        <div style="padding:12px 20px;background:var(--surface2);border-bottom:1px solid var(--border);display:flex;gap:20px">
            <span style="font-size:13px">
                <span style="font-weight:700;color:var(--green)">{{ $presentes_count }}</span>
                <span style="color:var(--text-soft)"> presentes</span>
            </span>
            <span style="font-size:13px">
                <span style="font-weight:700;color:var(--red)">{{ $total - $presentes_count }}</span>
                <span style="color:var(--text-soft)"> ausentes</span>
            </span>
            <span style="font-size:13px">
                <span style="font-weight:700;color:var(--purple)">{{ $total > 0 ? round($presentes_count/$total*100) : 0 }}%</span>
                <span style="color:var(--text-soft)"> frequência</span>
            </span>
        </div>
        @endif

        @foreach($aula->frequencias as $freq)
        @php $nome = $freq->matricula->aluno->usuario->nome ?? '-'; @endphp
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border)"
             id="row-{{ $freq->id }}">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:12px;
                            background:{{ $freq->presente ? 'var(--green-bg)' : 'var(--red-bg)' }};
                            display:flex;align-items:center;justify-content:center;flex-shrink:0"
                     id="avatar-{{ $freq->id }}">
                    <i class="bi bi-person" style="color:{{ $freq->presente ? 'var(--green)' : 'var(--red)' }};font-size:16px"
                       id="icon-{{ $freq->id }}"></i>
                </div>
                <div style="font-size:14px;font-weight:600">{{ $nome }}</div>
            </div>

            <div style="display:flex;align-items:center;gap:16px">
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                    <input type="checkbox" name="presentes[]" value="{{ $freq->matricula_id }}"
                           class="freq-check" data-id="{{ $freq->id }}"
                           {{ $freq->presente ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--purple)">
                    <span style="font-size:13px;color:var(--text-soft)">Presente</span>
                </label>
            </div>
        </div>
        @endforeach

        <div style="padding:16px 20px;display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary px-5">
                <i class="bi bi-check-lg me-2"></i>Salvar Frequência
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.querySelectorAll('.freq-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const id = this.dataset.id;
        const pres = this.checked;
        document.getElementById('avatar-' + id).style.background = pres ? 'var(--green-bg)' : 'var(--red-bg)';
        document.getElementById('icon-' + id).style.color = pres ? 'var(--green)' : 'var(--red)';
    });
});

function toggleAll(present) {
    document.querySelectorAll('.freq-check').forEach(cb => {
        cb.checked = present;
        cb.dispatchEvent(new Event('change'));
    });
}
</script>
@endpush
@endsection
