@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item active">Comunicados</li>
@endsection

@section('content')
<div class="sp-page-hdr">
    <div>
        <h1 class="sp-page-hdr-title">Comunicados</h1>
        <div class="sp-page-hdr-sub">Avisos e comunicações para alunos e responsáveis</div>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formNovoComunicado" data-no-loading>
        <i class="bi bi-plus-lg me-1"></i>Novo comunicado
    </button>
</div>

{{-- Form novo comunicado --}}
<div class="collapse mb-4" id="formNovoComunicado">
    <div class="sp-card">
        <div class="section-label mb-3">Novo comunicado</div>
        <form method="POST" action="{{ route('comunicados.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo') }}" placeholder="Ex.: Reunião de pais — 15/06" required>
                    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Destinatário</label>
                    <select name="destino" class="form-select" id="destinoSelect" onchange="toggleTurma(this.value)">
                        <option value="todos" {{ old('destino')=='todos'?'selected':'' }}>Todos</option>
                        <option value="alunos" {{ old('destino')=='alunos'?'selected':'' }}>Alunos</option>
                        <option value="responsaveis" {{ old('destino')=='responsaveis'?'selected':'' }}>Responsáveis</option>
                        <option value="professores" {{ old('destino')=='professores'?'selected':'' }}>Professores</option>
                        <option value="turma" {{ old('destino')=='turma'?'selected':'' }}>Turma específica</option>
                    </select>
                </div>
                <div class="col-md-4" id="turmaField" style="{{ old('destino')=='turma'?'':'display:none' }}">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select @error('turma_id') is-invalid @enderror">
                        <option value="">Selecionar...</option>
                        @foreach($turmas as $t)
                        <option value="{{ $t->id }}" {{ old('turma_id')==$t->id?'selected':'' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                    @error('turma_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="fixado" id="fixadoCheck" value="1" {{ old('fixado')?'checked':'' }}>
                        <label class="form-check-label" for="fixadoCheck" style="font-size:13px;color:var(--text)">
                            Fixar no topo
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Mensagem</label>
                    <textarea name="corpo" class="form-control @error('corpo') is-invalid @enderror"
                              rows="5" placeholder="Escreva o comunicado aqui..." required>{{ old('corpo') }}</textarea>
                    @error('corpo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send me-1"></i>Publicar
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#formNovoComunicado">
                        Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Lista --}}
<div class="sp-card p-0">
    @forelse($comunicados as $c)
    <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:var(--border)">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div style="flex:1">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    @if($c->fixado)
                    <span class="badge-sp badge-amber"><i class="bi bi-pin-angle me-1"></i>Fixado</span>
                    @endif
                    @if(!$c->publicado)
                    <span class="badge-sp badge-muted">Despublicado</span>
                    @endif
                    <span class="badge-sp badge-blue">
                        {{ ['todos'=>'Todos','alunos'=>'Alunos','responsaveis'=>'Responsáveis','professores'=>'Professores','turma'=>'Turma'][$c->destino] ?? $c->destino }}
                        @if($c->turma) — {{ $c->turma->nome }} @endif
                    </span>
                </div>
                <h6 class="mb-1 fw-bold" style="font-size:15px">{{ $c->titulo }}</h6>
                <p class="mb-2" style="font-size:13px;color:var(--text-soft);white-space:pre-wrap">{{ Str::limit($c->corpo, 200) }}</p>
                <div style="font-size:11px;color:var(--text-soft)">
                    <i class="bi bi-person me-1"></i>{{ $c->autor->nome ?? '-' }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-clock me-1"></i>{{ $c->criado_em->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <form method="POST" action="{{ route('comunicados.toggle', $c) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $c->publicado ? 'Despublicar' : 'Publicar' }}">
                        <i class="bi {{ $c->publicado ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                    </button>
                </form>
                <form method="POST" action="{{ route('comunicados.destroy', $c) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover"
                            onclick="return confirm('Remover comunicado?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="color:var(--text-soft)">
        <i class="bi bi-megaphone" style="font-size:36px;display:block;margin-bottom:10px;opacity:.4"></i>
        <div style="font-weight:600">Nenhum comunicado ainda</div>
        <div style="font-size:13px">Clique em "Novo comunicado" para publicar.</div>
    </div>
    @endforelse
</div>

@if($comunicados->hasPages())
<div class="mt-3">{{ $comunicados->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
function toggleTurma(v) {
    document.getElementById('turmaField').style.display = v === 'turma' ? '' : 'none';
}
</script>
@endpush
