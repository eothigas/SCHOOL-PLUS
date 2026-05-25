@extends('layouts.app')

@section('title', 'Meu Dashboard')

@section('content')

{{-- WELCOME BANNER --}}
<div class="welcome-banner">
    <div class="welcome-banner-text">
        <div class="date">
            <i class="bi bi-calendar3 me-1"></i>
            {{ ucfirst(now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
        </div>
        <h2>Olá, {{ explode(' ', session('usuario_nome'))[0] }}!</h2>
        <p>Veja suas turmas e aulas do dia.</p>
    </div>
    <div class="d-none d-md-block" style="position:relative;z-index:1">
        <svg width="110" height="90" viewBox="0 0 110 90" fill="none">
            <rect x="10" y="10" width="90" height="65" rx="10" fill="rgba(255,255,255,.12)"/>
            <rect x="20" y="22" width="30" height="4" rx="2" fill="rgba(255,255,255,.6)"/>
            <rect x="20" y="32" width="22" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
            <rect x="20" y="40" width="26" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
            <rect x="60" y="22" width="28" height="28" rx="7" fill="rgba(255,255,255,.2)"/>
            <rect x="67" y="29" width="14" height="3" rx="1.5" fill="rgba(255,255,255,.6)"/>
            <rect x="67" y="35" width="10" height="3" rx="1.5" fill="rgba(255,255,255,.4)"/>
            <rect x="20" y="52" width="70" height="1.5" rx="1" fill="rgba(255,255,255,.15)"/>
            <circle cx="27" cy="63" r="5" fill="rgba(255,255,255,.4)"/>
            <circle cx="44" cy="63" r="5" fill="rgba(255,255,255,.25)"/>
            <circle cx="61" cy="63" r="5" fill="rgba(255,255,255,.25)"/>
        </svg>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--purple-light)">
                <i class="bi bi-grid-fill" style="color:var(--purple)"></i>
            </div>
            <div class="stat-num">{{ $stats['turmas'] }}</div>
            <div class="stat-label">Minhas Turmas</div>
            <div class="stat-trend" style="color:var(--text-soft)"><i class="bi bi-dot"></i> Período atual</div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--blue-bg)">
                <i class="bi bi-journal-text" style="color:var(--blue)"></i>
            </div>
            <div class="stat-num">{{ $stats['disciplinas'] }}</div>
            <div class="stat-label">Disciplinas</div>
            <div class="stat-trend" style="color:var(--text-soft)"><i class="bi bi-dot"></i> Atribuídas</div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--green-bg)">
                <i class="bi bi-calendar-check-fill" style="color:var(--green)"></i>
            </div>
            <div class="stat-num">{{ $stats['aulas_mes'] }}</div>
            <div class="stat-label">Aulas no Mês</div>
            <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> {{ now()->locale('pt_BR')->isoFormat('MMMM') }}</div>
        </div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="row g-3">

    {{-- Minhas turmas/disciplinas --}}
    <div class="col-lg-7">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:14px;font-weight:700">Minhas Turmas</div>
                    <div style="font-size:11px;color:var(--text-soft)">Clique para abrir o diário</div>
                </div>
                <a href="{{ route('professor.minhas-turmas') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @forelse($turmaDiscs->groupBy('turma_id') as $turmaId => $discs)
            @php $turma = $discs->first()->turma; @endphp
            <div style="padding:14px 20px;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                    <div style="width:32px;height:32px;border-radius:9px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-grid-fill" style="color:var(--purple);font-size:14px"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700">{{ $turma->nome ?? 'Turma' }}</div>
                        <div style="font-size:11px;color:var(--text-soft)">{{ $turma->curso->nome ?? '' }} &bull; {{ $turma->periodo->nome ?? '' }}</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 ps-1">
                    @foreach($discs as $td)
                    <a href="{{ route('diario.index', $td->id) }}"
                       style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;
                              background:var(--surface2);border:1.5px solid var(--border);border-radius:8px;
                              text-decoration:none;font-size:12px;color:var(--text);font-weight:600;
                              transition:border-color .15s,background .15s"
                       onmouseover="this.style.borderColor='var(--purple-mid)';this.style.background='var(--purple-light)';this.style.color='var(--purple)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)';this.style.color='var(--text)'">
                        <i class="bi bi-journal-text" style="font-size:11px"></i>
                        {{ $td->disciplina->nome ?? 'Disciplina' }}
                    </a>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="sp-empty">
                <i class="bi bi-journal-x"></i>
                <div class="sp-empty-title">Nenhuma turma atribuída</div>
                <div class="sp-empty-sub">Aguarde a atribuição de turmas pela secretaria.</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Últimas aulas --}}
    <div class="col-lg-5">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border)">
                <div style="font-size:14px;font-weight:700">Últimas Aulas</div>
                <div style="font-size:11px;color:var(--text-soft)">Registradas recentemente</div>
            </div>
            @forelse($ultimasAulas as $aula)
            @php $td = $aula->turmaDisc; @endphp
            <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border)">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-calendar2-check" style="color:var(--green);font-size:14px"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $td?->disciplina->nome ?? '—' }}
                    </div>
                    <div style="font-size:11px;color:var(--text-soft)">{{ $td?->turma->nome ?? '' }}</div>
                    <div style="font-size:11px;color:var(--text-soft);margin-top:1px">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($aula->data_aula)->locale('pt_BR')->isoFormat('D [de] MMM') }}
                    </div>
                </div>
                <a href="{{ route('diario.aula', [$td?->id, $aula->id]) }}" class="icon-btn" title="Ver aula">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @empty
            <div class="sp-empty">
                <i class="bi bi-calendar2-x"></i>
                <div class="sp-empty-title">Nenhuma aula registrada</div>
                <div class="sp-empty-sub">Acesse seu diário para registrar.</div>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section('right-panel')
<div style="text-align:center;margin-bottom:24px">
    <div style="width:64px;height:64px;border-radius:20px;background:var(--purple);
                display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
        <i class="bi bi-person-badge-fill" style="font-size:28px;color:#fff"></i>
    </div>
    <div style="font-size:15px;font-weight:700;color:var(--text)">{{ session('usuario_nome') }}</div>
    <div style="font-size:12px;color:var(--text-soft);margin-top:2px">{{ session('usuario_email') }}</div>
    <div class="mt-2"><span class="badge-sp badge-purple">Professor</span></div>
</div>

<hr class="sp-divider">

<div style="margin-bottom:20px">
    <div class="section-label">Minhas Turmas</div>
    @forelse($turmaDiscs->groupBy('turma_id') as $turmaId => $discs)
    @php $turma = $discs->first()->turma; @endphp
    <div style="margin-bottom:10px">
        <div style="font-size:12px;font-weight:700;margin-bottom:4px">{{ $turma->nome ?? 'Turma' }}</div>
        @foreach($discs as $td)
        <a href="{{ route('diario.index', $td->id) }}"
           style="display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:9px;
                  text-decoration:none;transition:background .15s;margin-bottom:2px"
           onmouseover="this.style.background='var(--purple-light)'"
           onmouseout="this.style.background='transparent'">
            <i class="bi bi-journal-text" style="color:var(--purple);font-size:13px;width:16px;text-align:center"></i>
            <span style="font-size:12px;color:var(--text)">{{ $td->disciplina->nome ?? '—' }}</span>
            <i class="bi bi-chevron-right ms-auto" style="color:var(--text-soft);font-size:9px"></i>
        </a>
        @endforeach
    </div>
    @empty
    <div style="font-size:13px;color:var(--text-soft);text-align:center;padding:16px">Nenhuma turma.</div>
    @endforelse
</div>

<hr class="sp-divider">

<div>
    <div class="section-label">Hoje</div>
    <div style="background:var(--purple-light);border:1.5px solid var(--border);border-radius:14px;padding:16px;text-align:center">
        <div style="font-size:36px;font-weight:800;color:var(--purple);line-height:1">{{ now()->format('d') }}</div>
        <div style="font-size:13px;color:var(--text-soft);margin-top:4px">
            {{ ucfirst(now()->locale('pt_BR')->isoFormat('dddd')) }},
            {{ now()->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
        </div>
    </div>
</div>
@endsection
