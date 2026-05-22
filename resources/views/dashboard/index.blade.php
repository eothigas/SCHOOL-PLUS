@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard')

@section('content')

{{-- WELCOME BANNER ─────────────────────────────────── --}}
<div class="welcome-banner">
    <div class="welcome-banner-text">
        <div class="date">
            <i class="bi bi-calendar3 me-1"></i>
            {{ ucfirst(now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
        </div>
        <h2>Olá, {{ explode(' ', session('usuario_nome'))[0] }}!</h2>
        <p>Veja o resumo do dia da sua escola.</p>
    </div>
    <div class="d-none d-md-block" style="position:relative;z-index:1">
        <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
            <ellipse cx="60" cy="90" rx="40" ry="8" fill="rgba(255,255,255,.1)"/>
            <polygon points="60,10 90,28 60,46 30,28" fill="rgba(255,255,255,.85)"/>
            <rect x="58" y="28" width="4" height="14" rx="2" fill="rgba(255,255,255,.6)"/>
            <circle cx="60" cy="46" r="5" fill="rgba(255,255,255,.6)"/>
            <rect x="20" y="56" width="80" height="8" rx="4" fill="rgba(255,255,255,.25)"/>
            <rect x="28" y="64" width="6" height="20" rx="3" fill="rgba(255,255,255,.2)"/>
            <rect x="86" y="64" width="6" height="20" rx="3" fill="rgba(255,255,255,.2)"/>
            <rect x="38" y="40" width="22" height="18" rx="4" fill="rgba(255,255,255,.2)"/>
            <rect x="38" y="40" width="3" height="18" rx="1.5" fill="rgba(255,255,255,.4)"/>
            <rect x="65" y="42" width="18" height="16" rx="4" fill="rgba(255,255,255,.15)"/>
        </svg>
    </div>
</div>

{{-- STAT CARDS ─────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--purple-light)">
                <i class="bi bi-people-fill" style="color:var(--purple)"></i>
            </div>
            <div class="stat-num">{{ number_format($stats['alunos']) }}</div>
            <div class="stat-label">Alunos</div>
            <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> Total cadastrado</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--green-bg)">
                <i class="bi bi-grid-fill" style="color:var(--green)"></i>
            </div>
            <div class="stat-num">{{ number_format($stats['turmas']) }}</div>
            <div class="stat-label">Turmas</div>
            <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> Ativas no sistema</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--amber-bg)">
                <i class="bi bi-clipboard-check-fill" style="color:var(--amber)"></i>
            </div>
            <div class="stat-num">{{ number_format($stats['matriculas']) }}</div>
            <div class="stat-label">Matrículas Ativas</div>
            <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> Status ativo</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--blue-bg)">
                <i class="bi bi-calendar3-fill" style="color:var(--blue)"></i>
            </div>
            <div class="stat-num">{{ $stats['periodos'] }}</div>
            <div class="stat-label">Períodos Ativos</div>
            <div class="stat-trend" style="color:var(--text-soft)"><i class="bi bi-dot"></i> Ano letivo</div>
        </div>
    </div>
</div>

{{-- MAIN GRID ───────────────────────────────────────── --}}
<div class="row g-3">

    {{-- Turmas recentes --}}
    <div class="col-lg-7">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:14px;font-weight:700">Turmas Recentes</div>
                    <div style="font-size:11px;color:var(--text-soft)">Últimas turmas cadastradas</div>
                </div>
                <a href="{{ route('turmas.index') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <table class="sp-table">
                <thead>
                    <tr><th>Turma</th><th>Curso</th><th>Turno</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($turmas_recentes as $turma)
                    <tr>
                        <td>
                            <a href="{{ route('turmas.show', $turma) }}" style="color:var(--purple);font-weight:600;text-decoration:none">
                                {{ $turma->nome }}
                            </a>
                        </td>
                        <td style="color:var(--text-soft);font-size:12px">{{ $turma->curso->nome ?? '—' }}</td>
                        <td><span class="badge-sp badge-purple">{{ ucfirst($turma->turno) }}</span></td>
                        <td><span class="badge-status-{{ $turma->status }}">{{ str_replace('_',' ',$turma->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-soft);padding:32px">
                        Nenhuma turma. <a href="{{ route('turmas.create') }}" style="color:var(--purple)">Criar agora</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Matrículas recentes --}}
    <div class="col-lg-5">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:14px;font-weight:700">Matrículas Recentes</div>
                    <div style="font-size:11px;color:var(--text-soft)">Últimas realizadas</div>
                </div>
                <a href="{{ route('matriculas.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova
                </a>
            </div>
            <div>
                @forelse($matriculas_recentes as $mat)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border)">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;border-radius:12px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-person" style="color:var(--purple);font-size:16px"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600">{{ $mat->aluno->usuario->nome ?? '—' }}</div>
                            <div style="font-size:11px;color:var(--text-soft)">{{ $mat->turma->nome ?? '—' }}</div>
                        </div>
                    </div>
                    <span class="badge-status-{{ $mat->status }}">{{ $mat->status }}</span>
                </div>
                @empty
                <div style="text-align:center;color:var(--text-soft);padding:32px;font-size:14px">Nenhuma matrícula.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ações rápidas --}}
    <div class="col-12">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--text-soft);margin-bottom:12px;font-weight:700">
            Ações Rápidas
        </div>
        <div class="row g-2">
            @foreach([
                ['route'=>'alunos.create',    'icon'=>'bi-person-plus',    'label'=>'Novo Aluno',   'color'=>'var(--purple)','bg'=>'var(--purple-light)'],
                ['route'=>'turmas.create',    'icon'=>'bi-grid-plus',      'label'=>'Nova Turma',   'color'=>'var(--green)', 'bg'=>'var(--green-bg)'],
                ['route'=>'matriculas.create','icon'=>'bi-clipboard-plus', 'label'=>'Matricular',   'color'=>'var(--amber)', 'bg'=>'var(--amber-bg)'],
                ['route'=>'cursos.create',    'icon'=>'bi-book',           'label'=>'Novo Curso',   'color'=>'var(--blue)',  'bg'=>'var(--blue-bg)'],
                ['route'=>'disciplinas.create','icon'=>'bi-journal-plus',  'label'=>'Disciplina',   'color'=>'var(--purple)','bg'=>'var(--purple-light)'],
                ['route'=>'professores.create','icon'=>'bi-person-badge',  'label'=>'Professor',    'color'=>'var(--green)', 'bg'=>'var(--green-bg)'],
            ] as $action)
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route($action['route']) }}"
                   style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:18px 12px;
                          background:var(--surface);border:1.5px solid var(--border);border-radius:16px;
                          text-decoration:none;transition:border-color .2s,transform .2s,box-shadow .2s"
                   onmouseover="this.style.borderColor='{{ $action['color'] }}';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(124,58,237,.1)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.transform='';this.style.boxShadow=''">
                    <div style="width:42px;height:42px;border-radius:12px;background:{{ $action['bg'] }};display:flex;align-items:center;justify-content:center">
                        <i class="bi {{ $action['icon'] }}" style="color:{{ $action['color'] }};font-size:18px"></i>
                    </div>
                    <span style="font-size:12px;color:var(--text);font-weight:600;text-align:center">{{ $action['label'] }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('right-panel')
{{-- Perfil do usuário --}}
<div style="text-align:center;margin-bottom:24px">
    <div style="width:64px;height:64px;border-radius:20px;background:var(--purple);
                display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
        <i class="bi bi-person-fill" style="font-size:28px;color:#fff"></i>
    </div>
    <div style="font-size:15px;font-weight:700;color:var(--text)">{{ session('usuario_nome') }}</div>
    <div style="font-size:12px;color:var(--text-soft);margin-top:2px">{{ session('usuario_email') }}</div>
    <div class="mt-2">
        <span class="badge-sp badge-purple">{{ ucfirst(session('usuario_perfil')) }}</span>
    </div>
</div>

<hr class="sp-divider">

{{-- Resumo rápido --}}
<div style="margin-bottom:20px">
    <div class="section-label">Resumo</div>
    @foreach([
        ['label' => 'Alunos',     'val' => $stats['alunos'],     'color' => 'var(--purple)'],
        ['label' => 'Turmas',     'val' => $stats['turmas'],     'color' => 'var(--green)'],
        ['label' => 'Matrículas', 'val' => $stats['matriculas'], 'color' => 'var(--amber)'],
    ] as $item)
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <div style="display:flex;align-items:center;gap:8px">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $item['color'] }};flex-shrink:0"></div>
            <span style="font-size:13px;color:var(--text-soft)">{{ $item['label'] }}</span>
        </div>
        <span style="font-size:14px;font-weight:700;color:var(--text)">{{ $item['val'] }}</span>
    </div>
    @endforeach
</div>

<hr class="sp-divider">

{{-- Data atual --}}
<div style="margin-bottom:20px">
    <div class="section-label">Hoje</div>
    <div style="background:var(--purple-light);border:1.5px solid var(--border);border-radius:14px;padding:16px;text-align:center">
        <div style="font-size:36px;font-weight:800;color:var(--purple);line-height:1">{{ now()->format('d') }}</div>
        <div style="font-size:13px;color:var(--text-soft);margin-top:4px">
            {{ ucfirst(now()->locale('pt_BR')->isoFormat('dddd')) }},
            {{ now()->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
        </div>
    </div>
</div>

<hr class="sp-divider">

{{-- Links rápidos --}}
<div>
    <div class="section-label">Links Rápidos</div>
    @foreach([
        ['route' => 'alunos.index',     'icon' => 'bi-people',          'label' => 'Ver Alunos'],
        ['route' => 'turmas.index',     'icon' => 'bi-grid',            'label' => 'Ver Turmas'],
        ['route' => 'matriculas.index', 'icon' => 'bi-clipboard-check', 'label' => 'Ver Matrículas'],
        ['route' => 'disciplinas.index','icon' => 'bi-journal-text',    'label' => 'Disciplinas'],
        ['route' => 'professores.index','icon' => 'bi-person-badge',    'label' => 'Professores'],
    ] as $link)
    <a href="{{ route($link['route']) }}"
       style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;
              text-decoration:none;transition:background .15s;margin-bottom:4px"
       onmouseover="this.style.background='var(--purple-light)'"
       onmouseout="this.style.background='transparent'">
        <i class="bi {{ $link['icon'] }}" style="color:var(--purple);font-size:15px;width:18px;text-align:center"></i>
        <span style="font-size:13px;color:var(--text)">{{ $link['label'] }}</span>
        <i class="bi bi-chevron-right ms-auto" style="color:var(--text-soft);font-size:10px"></i>
    </a>
    @endforeach
</div>
@endsection
