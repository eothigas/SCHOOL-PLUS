@extends('layouts.app')

@section('title', $aluno->usuario->nome ?? 'Aluno')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}" class="text-decoration-none text-muted">Alunos</a></li>
    <li class="breadcrumb-item active">{{ $aluno->usuario->nome ?? '-' }}</li>
@endsection

@push('styles')
<style>
/* ── PROFILE HEADER ─────────────────────────────────────────── */
.profile-banner {
    background: linear-gradient(135deg, var(--purple) 0%, #9333ea 60%, #a855f7 100%);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 24px;
}
.profile-banner::before {
    content:''; position:absolute;
    width:200px; height:200px; border-radius:50%;
    background:rgba(255,255,255,.06);
    top:-60px; right:120px;
}
.profile-banner::after {
    content:''; position:absolute;
    width:130px; height:130px; border-radius:50%;
    background:rgba(255,255,255,.06);
    bottom:-40px; right:60px;
}
.profile-avatar {
    width: 72px; height: 72px;
    border-radius: 20px;
    background: rgba(255,255,255,.22);
    backdrop-filter: blur(8px);
    border: 2px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    letter-spacing: -.02em;
    position: relative; z-index: 1;
}
.profile-info { flex: 1; position: relative; z-index: 1; }
.profile-info .name {
    font-size: 22px; font-weight: 800;
    color: #fff; margin-bottom: 4px; letter-spacing: -.02em;
}
.profile-info .sub {
    font-size: 13px; color: rgba(255,255,255,.72);
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.profile-info .sub .chip {
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 11px; font-weight: 600;
    color: #fff;
    display: flex; align-items: center; gap: 4px;
}
.profile-actions { position: relative; z-index: 1; display: flex; gap: 8px; flex-shrink: 0; }
.profile-actions .btn-action {
    height: 36px; padding: 0 16px;
    border-radius: 10px;
    font-size: 13px; font-weight: 600;
    border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    text-decoration: none; transition: opacity .15s, transform .1s;
}
.profile-actions .btn-action:hover { opacity: .88; transform: translateY(-1px); }
.btn-action-white  { background: #fff; color: var(--purple); }
.btn-action-ghost  { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.3) !important; }

/* ── INFO FIELDS ────────────────────────────────────────────── */
.info-grid { display: flex; flex-direction: column; gap: 4px; }
.info-row {
    display: flex; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    gap: 12px;
}
.info-row:last-child { border-bottom: none; }
.info-icon {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.info-label {
    font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--text-soft);
    min-width: 90px;
}
.info-value {
    font-size: 13px; font-weight: 500;
    color: var(--text); flex: 1;
    word-break: break-word;
}
.info-value code {
    background: var(--purple-light);
    color: var(--purple);
    border-radius: 6px;
    padding: 2px 8px;
    font-size: 12px; font-weight: 700;
    font-family: inherit;
}

/* ── SECTION CARD HEADER ────────────────────────────────────── */
.card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--border);
}
.card-head-left {
    display: flex; align-items: center; gap: 10px;
}
.card-head-icon {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
}
.card-head-title { font-size: 14px; font-weight: 700; color: var(--text); }
.card-head-count {
    font-size: 11px; font-weight: 700;
    background: var(--purple-light); color: var(--purple);
    border-radius: 20px; padding: 1px 8px;
}

/* ── STATUS BADGES ──────────────────────────────────────────── */
.badge-status-ativa     { background:var(--green-bg);  color:var(--green); }
.badge-status-inativa   { background:#f3f4f6;          color:#6b7280;      }
.badge-status-cancelada { background:var(--red-bg);    color:var(--red);   }
.badge-status-trancada  { background:var(--amber-bg);  color:var(--amber); }

/* ── FILE CARD ──────────────────────────────────────────────── */
.file-row {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    transition: background .12s;
}
.file-row:last-child { border-bottom: none; }
.file-row:hover { background: var(--surface2); }
.file-thumb {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.file-ext {
    font-size: 9px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .04em;
    background: var(--purple); color: #fff;
    border-radius: 4px; padding: 1px 5px;
}

/* ── RESP CARD ──────────────────────────────────────────────── */
.resp-row {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
}
.resp-row:last-child { border-bottom: none; }
.resp-avatar {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--purple-light); color: var(--purple);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; flex-shrink: 0;
}
.resp-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 4px; }
.resp-contacts { display: flex; gap: 14px; flex-wrap: wrap; }
.resp-contact-item { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--text-soft); }

/* ── INLINE FORM ────────────────────────────────────────────── */
.inline-form {
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 16px;
    margin-top: 12px;
}
.inline-form-title {
    font-size: 11px; text-transform: uppercase;
    letter-spacing: .08em; color: var(--text-soft);
    font-weight: 700; margin-bottom: 12px;
}

/* ── ACTION ICON BTN ────────────────────────────────────────── */
.icon-btn {
    width: 32px; height: 32px;
    border-radius: 9px; border: 1.5px solid var(--border);
    background: var(--surface); display: flex;
    align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px;
    color: var(--text-soft); transition: all .12s;
    text-decoration: none;
}
.icon-btn:hover           { background: var(--purple-light); color: var(--purple); border-color: var(--purple-mid); }
.icon-btn.danger:hover    { background: var(--red-bg); color: var(--red); border-color: #fca5a5; }
.icon-btn.primary:hover   { background: var(--purple-light); color: var(--purple); border-color: var(--purple); }
</style>
@endpush

@section('content')

@php
    $nome      = $aluno->usuario->nome ?? '-';
    $iniciais  = collect(explode(' ', $nome))->filter()->take(2)->map(fn($p) => strtoupper(substr($p,0,1)))->join('');
    $matriculaAtiva = $aluno->matriculas->firstWhere('status', 'ativa');
@endphp

{{-- ── PROFILE BANNER ──────────────────────────────────────── --}}
<div class="profile-banner">
    <div class="profile-avatar">{{ $iniciais }}</div>
    <div class="profile-info">
        <div class="name">{{ $nome }}</div>
        <div class="sub">
            <span class="chip"><i class="bi bi-hash"></i>{{ $aluno->matricula }}</span>
            @if($matriculaAtiva)
            <span class="chip"><i class="bi bi-mortarboard"></i>{{ $matriculaAtiva->turma->nome ?? '-' }}</span>
            <span class="chip"><i class="bi bi-book"></i>{{ $matriculaAtiva->turma->curso->nome ?? '-' }}</span>
            @endif
            @if($aluno->usuario->email)
            <span class="chip"><i class="bi bi-envelope"></i>{{ $aluno->usuario->email }}</span>
            @endif
        </div>
    </div>
    <div class="profile-actions">
        <a href="{{ route('boletim.show', $aluno) }}" class="btn-action btn-action-white">
            <i class="bi bi-bar-chart-line"></i>Boletim
        </a>
        <a href="{{ route('alunos.edit', $aluno) }}" class="btn-action btn-action-ghost">
            <i class="bi bi-pencil"></i>Editar
        </a>
        <a href="{{ route('alunos.index') }}" class="btn-action btn-action-ghost">
            <i class="bi bi-arrow-left"></i>Voltar
        </a>
    </div>
</div>

{{-- ── COLUMNS ─────────────────────────────────────────────── --}}
<div class="row g-3 align-items-start">

    {{-- LEFT: dados pessoais --}}
    <div class="col-md-4">
        <div class="sp-card p-0">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon" style="background:var(--purple-light);color:var(--purple)">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                    <span class="card-head-title">Dados Pessoais</span>
                </div>
            </div>
            <div class="px-4 py-2 info-grid">
                <div class="info-row">
                    <div class="info-icon" style="background:var(--purple-light);color:var(--purple)"><i class="bi bi-hash"></i></div>
                    <div class="info-label">Matrícula</div>
                    <div class="info-value"><code>{{ $aluno->matricula }}</code></div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:var(--blue-bg);color:var(--blue)"><i class="bi bi-envelope-fill"></i></div>
                    <div class="info-label">E-mail</div>
                    <div class="info-value">{{ $aluno->usuario->email ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:var(--green-bg);color:var(--green)"><i class="bi bi-card-text"></i></div>
                    <div class="info-label">CPF</div>
                    <div class="info-value">{{ $aluno->cpf ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:var(--amber-bg);color:var(--amber)"><i class="bi bi-cake2-fill"></i></div>
                    <div class="info-label">Nascimento</div>
                    <div class="info-value">{{ $aluno->data_nascimento?->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:#fce7f3;color:#db2777"><i class="bi bi-gender-ambiguous"></i></div>
                    <div class="info-label">Sexo</div>
                    <div class="info-value">{{ $aluno->sexo === 'M' ? 'Masculino' : ($aluno->sexo === 'F' ? 'Feminino' : ($aluno->sexo ?? '-')) }}</div>
                </div>
                @if($aluno->usuario->telefone ?? null)
                <div class="info-row">
                    <div class="info-icon" style="background:var(--green-bg);color:var(--green)"><i class="bi bi-telephone-fill"></i></div>
                    <div class="info-label">Telefone</div>
                    <div class="info-value">{{ $aluno->usuario->telefone }}</div>
                </div>
                @endif
                @if($aluno->endereco)
                <div class="info-row">
                    <div class="info-icon" style="background:var(--blue-bg);color:var(--blue)"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="info-label">Endereço</div>
                    <div class="info-value" style="font-size:12px">
                        {{ $aluno->endereco }}@if($aluno->cidade), {{ $aluno->cidade }}@endif
                        @if($aluno->estado) &ndash; {{ $aluno->estado }}@endif
                    </div>
                </div>
                @endif
                @if($aluno->nome_pai || $aluno->nome_mae)
                <div class="info-row">
                    <div class="info-icon" style="background:var(--amber-bg);color:var(--amber)"><i class="bi bi-people-fill"></i></div>
                    <div class="info-label">Pais</div>
                    <div class="info-value" style="font-size:12px">
                        @if($aluno->nome_pai)<div>{{ $aluno->nome_pai }}</div>@endif
                        @if($aluno->nome_mae)<div>{{ $aluno->nome_mae }}</div>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: matrículas, documentos, responsáveis --}}
    <div class="col-md-8 d-flex flex-column gap-3">

        {{-- MATRÍCULAS --}}
        <div class="sp-card p-0">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon" style="background:var(--green-bg);color:var(--green)">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                    <span class="card-head-title">Matrículas</span>
                    <span class="card-head-count">{{ $aluno->matriculas->count() }}</span>
                </div>
                <a href="{{ route('matriculas.create', ['aluno_id' => $aluno->id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova
                </a>
            </div>
            @forelse($aluno->matriculas as $mat)
            <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:var(--border)">
                <div style="width:42px;height:42px;border-radius:12px;background:var(--purple-light);color:var(--purple);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div style="flex:1">
                    <div style="font-weight:700;font-size:14px;margin-bottom:2px">{{ $mat->turma->nome ?? '-' }}</div>
                    <div style="font-size:12px;color:var(--text-soft)">
                        {{ $mat->turma->curso->nome ?? '-' }}
                        @if($mat->periodo) &nbsp;·&nbsp; <i class="bi bi-calendar3 me-1"></i>{{ $mat->periodo->nome }} @endif
                    </div>
                </div>
                <span class="badge-sp badge-status-{{ $mat->status }}" style="font-size:11px;text-transform:capitalize">
                    @if($mat->status === 'ativa')<i class="bi bi-check-circle me-1"></i>@endif
                    {{ ucfirst($mat->status) }}
                </span>
            </div>
            @empty
            <div class="text-center py-5" style="color:var(--text-soft)">
                <i class="bi bi-clipboard-x" style="font-size:28px;display:block;opacity:.35;margin-bottom:8px"></i>
                <div style="font-size:13px;font-weight:500">Nenhuma matrícula.</div>
            </div>
            @endforelse
        </div>

        {{-- DOCUMENTOS --}}
        <div class="sp-card p-0">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon" style="background:var(--blue-bg);color:var(--blue)">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <span class="card-head-title">Documentos</span>
                    @if($aluno->documentos->count())
                    <span class="card-head-count">{{ $aluno->documentos->count() }}</span>
                    @endif
                </div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formNovoDoc">
                    <i class="bi bi-plus-lg me-1"></i>Enviar
                </button>
            </div>

            <div class="collapse" id="formNovoDoc">
                <form method="POST" action="{{ route('documentos.store', $aluno) }}"
                      enctype="multipart/form-data"
                      class="px-4 py-3" style="border-bottom:1px solid var(--border)">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                @foreach(\App\Models\DocumentoAluno::$tiposLabel as $val => $label)
                                <option value="{{ $val }}" {{ old('tipo')==$val?'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nome <span style="color:var(--text-soft)">(opcional)</span></label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" placeholder="Ex.: RG frente">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Arquivo <span style="color:var(--text-soft)">PDF · JPG · PNG</span></label>
                            <input type="file" name="arquivo" class="form-control @error('arquivo') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                            @error('arquivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-upload me-1"></i>Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @forelse($aluno->documentos as $doc)
            @php
                $ext = strtolower(pathinfo($doc->arquivo_url, PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg','jpeg','png','webp']);
                $isPdf = $ext === 'pdf';
                $docIcon = $isImg ? 'bi-image-fill' : ($isPdf ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill');
                $docColor = $isImg ? ['#dcfce7','#16a34a'] : ($isPdf ? ['#fee2e2','#dc2626'] : ['#dbeafe','#2563eb']);
            @endphp
            <div class="file-row">
                <div class="file-thumb" style="background:{{ $docColor[0] }};color:{{ $docColor[1] }}">
                    <i class="bi {{ $docIcon }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->nome }}</div>
                    <div style="font-size:11px;color:var(--text-soft);display:flex;align-items:center;gap:6px;margin-top:2px">
                        <span class="badge-sp" style="background:{{ $docColor[0] }};color:{{ $docColor[1] }};padding:1px 6px;font-size:9px;font-weight:700">{{ strtoupper($ext) }}</span>
                        {{ $doc->tipo_label }}
                        <span>·</span>
                        {{ $doc->criado_em->format('d/m/Y') }}
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ asset('storage/' . $doc->arquivo_url) }}" target="_blank"
                       class="icon-btn" title="Visualizar" data-no-loading>
                        <i class="bi bi-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('documentos.destroy', [$aluno, $doc]) }}" data-no-loading>
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Remover"
                                onclick="return confirm('Remover {{ $doc->nome }}?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-4" style="color:var(--text-soft)">
                <i class="bi bi-folder2-open" style="font-size:26px;display:block;opacity:.35;margin-bottom:6px"></i>
                <div style="font-size:13px;font-weight:500">Nenhum documento enviado.</div>
            </div>
            @endforelse
        </div>

        {{-- RESPONSÁVEIS --}}
        <div class="sp-card p-0">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon" style="background:var(--amber-bg);color:var(--amber)">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="card-head-title">Responsáveis</span>
                    @if($aluno->responsaveis->count())
                    <span class="card-head-count">{{ $aluno->responsaveis->count() }}</span>
                    @endif
                </div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formNovoResp">
                    <i class="bi bi-plus-lg me-1"></i>Adicionar
                </button>
            </div>

            <div class="collapse" id="formNovoResp">
                <form method="POST" action="{{ route('responsaveis.store', $aluno) }}"
                      class="px-4 py-3" style="border-bottom:1px solid var(--border)">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-5">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Parentesco *</label>
                            <select name="parentesco" class="form-select" required>
                                <option value="">—</option>
                                @foreach(['Pai','Mãe','Avô/Avó','Tio/Tia','Responsável legal','Outro'] as $p)
                                <option value="{{ $p }}" {{ old('parentesco')==$p?'selected':'' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="{{ old('cpf') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-check-lg me-1"></i>Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @forelse($aluno->responsaveis as $resp)
            @php
                $u = $resp->usuario_id
                    ? \App\Models\Usuario::withoutGlobalScope('tenant')->find($resp->usuario_id)
                    : null;
                $portalAtivo = $u && $u->status;
                $respIniciais = collect(explode(' ', $resp->nome))->filter()->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->join('');
            @endphp
            <div class="resp-row">
                <div class="d-flex align-items-start gap-3">
                    <div class="resp-avatar">{{ $respIniciais }}</div>

                    <div style="flex:1">
                        <div class="resp-meta">
                            <span style="font-weight:700;font-size:14px">{{ $resp->nome }}</span>
                            <span class="badge-sp badge-purple" style="font-size:10px">{{ $resp->parentesco }}</span>
                            @if($resp->usuario_id)
                                @if($portalAtivo)
                                    <span class="badge-sp badge-green" style="font-size:10px">
                                        <i class="bi bi-shield-check me-1"></i>Portal ativo
                                    </span>
                                @else
                                    <span class="badge-sp badge-muted" style="font-size:10px">
                                        <i class="bi bi-shield-slash me-1"></i>Portal inativo
                                    </span>
                                @endif
                            @else
                                <span class="badge-sp badge-amber" style="font-size:10px">
                                    <i class="bi bi-shield me-1"></i>Sem acesso
                                </span>
                            @endif
                        </div>
                        <div class="resp-contacts">
                            @if($resp->telefone)
                            <div class="resp-contact-item">
                                <i class="bi bi-telephone"></i>{{ $resp->telefone }}
                            </div>
                            @endif
                            @if($resp->email)
                            <div class="resp-contact-item">
                                <i class="bi bi-envelope"></i>{{ $resp->email }}
                            </div>
                            @endif
                            @if($u)
                            <div class="resp-contact-item">
                                <i class="bi bi-person-badge"></i>{{ $u->email ?? '-' }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-1 flex-shrink-0">
                        <button class="icon-btn primary"
                                data-bs-toggle="collapse"
                                data-bs-target="#loginResp{{ $resp->id }}"
                                title="{{ $resp->usuario_id ? 'Atualizar acesso' : 'Criar acesso ao portal' }}">
                            <i class="bi bi-key-fill"></i>
                        </button>
                        @if($resp->usuario_id)
                        <form method="POST" action="{{ route('responsaveis.revogar', [$aluno, $resp]) }}" data-no-loading class="d-inline">
                            @csrf
                            <button type="submit" class="icon-btn" title="Revogar acesso"
                                    onclick="return confirm('Revogar acesso de {{ $resp->nome }}?')">
                                <i class="bi bi-shield-x"></i>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('responsaveis.destroy', [$aluno, $resp]) }}" data-no-loading class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn danger" title="Remover"
                                    onclick="return confirm('Remover {{ $resp->nome }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="collapse" id="loginResp{{ $resp->id }}">
                    <div class="inline-form">
                        <div class="inline-form-title">
                            <i class="bi bi-key me-1"></i>
                            {{ $resp->usuario_id ? 'Atualizar acesso ao portal' : 'Criar acesso ao portal' }}
                        </div>
                        <form method="POST" action="{{ route('responsaveis.login', [$aluno, $resp]) }}" data-no-loading>
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label class="form-label">E-mail de acesso</label>
                                    <input type="email" name="email"
                                           class="form-control @error('email_resp_'.$resp->id) is-invalid @enderror"
                                           value="{{ old('email', $resp->email) }}"
                                           placeholder="email@exemplo.com" required>
                                    @error('email_resp_'.$resp->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ $resp->usuario_id ? 'Nova senha' : 'Senha' }}</label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="mín. 6 caracteres"
                                           {{ $resp->usuario_id ? '' : 'required' }}>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-check-lg me-1"></i>Salvar acesso
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5" style="color:var(--text-soft)">
                <i class="bi bi-people" style="font-size:28px;display:block;opacity:.35;margin-bottom:8px"></i>
                <div style="font-size:13px;font-weight:500">Nenhum responsável cadastrado.</div>
            </div>
            @endforelse
        </div>

    </div>{{-- col-md-8 --}}
</div>
@endsection
