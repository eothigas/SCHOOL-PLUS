@extends('portal.layouts.app')
@section('title', 'Documentos')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-size:22px;font-weight:800;color:var(--text);margin:0">Documentos</h1>
        <p style="font-size:13px;color:var(--text-soft);margin:4px 0 0">Documentos arquivados pela escola</p>
    </div>
</div>

@if($documentos->isEmpty())
<div class="sp-card text-center py-5" style="color:var(--text-soft)">
    <i class="bi bi-folder2-open" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3"></i>
    <div style="font-weight:600;font-size:15px">Nenhum documento</div>
    <div style="font-size:13px;margin-top:4px">A escola ainda não enviou documentos.</div>
</div>
@else
<div class="sp-card p-0">
    <table class="sp-table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Nome</th>
                <th>Data</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td>
                    @php
                        $iconMap = [
                            'rg' => 'bi-person-vcard', 'cpf' => 'bi-person-vcard',
                            'foto' => 'bi-image', 'historico' => 'bi-journal-text',
                            'declaracao' => 'bi-file-earmark-text',
                            'comprovante_residencia' => 'bi-house',
                            'outro' => 'bi-file-earmark',
                        ];
                        $icon = $iconMap[$doc->tipo] ?? 'bi-file-earmark';
                    @endphp
                    <span class="badge-sp badge-blue">
                        <i class="bi {{ $icon }} me-1"></i>{{ $doc->tipo_label }}
                    </span>
                </td>
                <td style="font-weight:500">{{ $doc->nome }}</td>
                <td style="font-size:12px;color:var(--text-soft)">{{ $doc->criado_em->format('d/m/Y') }}</td>
                <td class="text-end">
                    <a href="{{ asset('storage/' . $doc->arquivo_url) }}"
                       target="_blank"
                       class="btn btn-sm"
                       style="background:var(--purple-light);color:var(--purple);border:none;border-radius:8px;font-weight:600">
                        <i class="bi bi-download me-1"></i>Baixar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
