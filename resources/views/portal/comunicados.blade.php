@extends('portal.layouts.app')
@section('title', 'Comunicados')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-size:22px;font-weight:800;color:var(--text);margin:0">Comunicados</h1>
        <p style="font-size:13px;color:var(--text-soft);margin:4px 0 0">Avisos e comunicações da escola</p>
    </div>
</div>

@forelse($comunicados as $comunicado)
@php
    $lida     = $comunicado->leituras->first();
    $usuarioId = session('portal_usuario_id');
@endphp
<div class="sp-card mb-3 comunicado-card {{ $lida ? '' : 'nao-lida' }}"
     id="comunicado-{{ $comunicado->id }}"
     style="{{ $lida ? '' : 'border-left: 3px solid var(--purple);' }}">

    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($comunicado->fixado)
            <span class="badge-sp badge-amber"><i class="bi bi-pin-angle me-1"></i>Fixado</span>
            @endif
            @if(!$lida)
            <span class="badge-sp badge-purple" style="font-size:10px">Novo</span>
            @endif
        </div>
        <span style="font-size:11px;color:var(--text-soft);white-space:nowrap">
            {{ $comunicado->criado_em->format('d/m/Y H:i') }}
        </span>
    </div>

    <h6 class="fw-bold mb-2" style="font-size:15px;color:var(--text)">{{ $comunicado->titulo }}</h6>
    <p style="font-size:14px;color:var(--text-soft);white-space:pre-wrap;margin:0">{{ $comunicado->corpo }}</p>

    @if($comunicado->autor)
    <div class="mt-3 pt-3" style="border-top:1px solid var(--border);font-size:11px;color:var(--text-soft)">
        <i class="bi bi-person me-1"></i>{{ $comunicado->autor->nome }}
    </div>
    @endif
</div>
@empty
<div class="sp-card text-center py-5" style="color:var(--text-soft)">
    <i class="bi bi-megaphone" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3"></i>
    <div style="font-weight:600;font-size:15px">Nenhum comunicado</div>
    <div style="font-size:13px;margin-top:4px">A escola não publicou comunicados ainda.</div>
</div>
@endforelse

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Marcar como lido automaticamente após 2s de visibilidade
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const card = entry.target;
                if (card.classList.contains('nao-lida')) {
                    const id = card.id.replace('comunicado-', '');
                    setTimeout(() => marcarLida(id, card), 1500);
                }
            }
        });
    }, { threshold: 0.6 });

    document.querySelectorAll('.comunicado-card.nao-lida').forEach(card => observer.observe(card));
});

function marcarLida(id, card) {
    fetch(`/portal/comunicados/${id}/lida`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    }).then(r => r.json()).then(data => {
        if (data.ok) {
            card.classList.remove('nao-lida');
            card.style.borderLeft = '';
            const badge = card.querySelector('.badge-purple');
            if (badge) badge.remove();
        }
    }).catch(() => {});
}
</script>
@endpush
