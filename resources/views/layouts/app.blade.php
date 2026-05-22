<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School+') — School+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --purple:      #7c3aed;
            --purple-dark: #5b21b6;
            --purple-light:#ede9fe;
            --purple-mid:  #a78bfa;
            --bg:          #ece8f8;
            --surface:     #ffffff;
            --surface2:    #f8f7ff;
            --border:      #e5e0f5;
            --text:        #1e1b4b;
            --text-soft:   #6b7280;
            --sidebar-open: 224px;
            --sidebar-closed: 72px;
            --right-w:     280px;
            --green:       #16a34a;
            --green-bg:    #dcfce7;
            --amber:       #d97706;
            --amber-bg:    #fef3c7;
            --red:         #dc2626;
            --red-bg:      #fee2e2;
            --blue:        #2563eb;
            --blue-bg:     #dbeafe;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', system-ui, sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
            padding: 12px;
            gap: 12px;
        }

        /* ════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-open);
            background: var(--purple);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            padding: 20px 12px 16px;
            gap: 2px;
            flex-shrink: 0;
            z-index: 100;
            overflow: hidden;
            transition: width .3s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 8px 32px rgba(124,58,237,.25);
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        /* header: logo + brand name */
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            flex-shrink: 0;
            overflow: hidden;
        }
        .sidebar-logo {
            width: 44px; height: 44px;
            background: rgba(255,255,255,.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
            backdrop-filter: blur(8px);
        }
        .sidebar-logo i { color: #fff; font-size: 20px; }
        .sidebar-brand {
            display: flex; flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
        }
        .sidebar-brand-name {
            color: #fff;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .04em;
            line-height: 1.2;
        }
        .sidebar-brand-sub {
            color: rgba(255,255,255,.55);
            font-size: 10px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* nav items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 12px;
            color: rgba(255,255,255,.72);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s, color .15s;
            text-decoration: none;
            overflow: hidden;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .nav-item i {
            font-size: 17px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            transition: transform .15s;
        }
        .nav-item:hover { background: rgba(255,255,255,.15); color: #fff; }
        .nav-item.active {
            background: rgba(255,255,255,.2);
            color: #fff;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.15);
        }

        /* section dividers */
        .nav-section {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.38);
            padding: 14px 12px 4px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* text labels (hidden when collapsed) */
        .nav-label {
            overflow: hidden;
            transition: opacity .2s ease, max-width .3s ease;
            max-width: 160px;
        }

        /* toggle button at bottom */
        .sidebar-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 12px;
            background: none;
            border: none;
            color: rgba(255,255,255,.55);
            font-size: 13.5px;
            cursor: pointer;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            flex-shrink: 0;
            transition: background .15s, color .15s;
        }
        .sidebar-toggle:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar-toggle i {
            font-size: 16px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            transition: transform .35s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-spacer { flex: 1; min-height: 8px; }

        /* ── COLLAPSED STATE ─────────────────────── */
        body.sb-closed .sidebar { width: var(--sidebar-closed); }

        body.sb-closed .nav-label,
        body.sb-closed .sidebar-brand,
        body.sb-closed .nav-section { opacity: 0; max-width: 0; }

        body.sb-closed .nav-section { padding-top: 6px; padding-bottom: 2px; }

        body.sb-closed .nav-item,
        body.sb-closed .sidebar-toggle { justify-content: center; padding-left: 0; padding-right: 0; }

        body.sb-closed .sidebar-header { justify-content: center; }

        body.sb-closed .sidebar-toggle i { transform: rotate(180deg); }

        /* ════════════════════════════════════════
           MAIN WRAPPER
        ════════════════════════════════════════ */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: var(--surface);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(124,58,237,.08);
        }

        /* ── TOPBAR ─────────────────────────────── */
        .topbar {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            border-radius: 20px 20px 0 0;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .topbar-search {
            position: relative;
            flex: 1;
            max-width: 360px;
        }
        .topbar-search i {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-soft);
            font-size: 14px;
        }
        .topbar-search input {
            width: 100%;
            padding: 9px 16px 9px 40px;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }
        .topbar-search input:focus { border-color: var(--purple-mid); }
        .topbar-search input::placeholder { color: var(--text-soft); }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 12px 5px 5px;
            border-radius: 40px;
            background: var(--purple-light);
            cursor: pointer;
            text-decoration: none;
        }
        .topbar-user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--purple);
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-user-avatar i { color: #fff; font-size: 15px; }
        .topbar-user-name { font-size: 13px; font-weight: 700; color: var(--purple); }
        .topbar-user-role { font-size: 11px; color: var(--text-soft); }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--purple-light);
            display: flex; align-items: center; justify-content: center;
            color: var(--purple);
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: background .15s;
        }
        .topbar-btn:hover { background: var(--border); }

        /* ── CONTENT AREA ───────────────────────── */
        .content-area {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        .page-content {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }
        .page-content::-webkit-scrollbar { width: 5px; }
        .page-content::-webkit-scrollbar-track { background: transparent; }
        .page-content::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* ── RIGHT PANEL ────────────────────────── */
        .right-panel {
            width: var(--right-w);
            background: var(--surface2);
            border-left: 1px solid var(--border);
            overflow-y: auto;
            padding: 24px 20px;
            flex-shrink: 0;
            border-radius: 0 20px 20px 0;
        }
        .right-panel::-webkit-scrollbar { width: 4px; }
        .right-panel::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* ── PAGE HEADER ────────────────────────── */
        .welcome-banner {
            background: linear-gradient(135deg, var(--purple) 0%, #9333ea 60%, #a855f7 100%);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            width: 240px; height: 240px; border-radius: 50%;
            background: rgba(255,255,255,.06);
            top: -60px; right: 80px;
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(255,255,255,.06);
            bottom: -50px; right: 200px;
        }
        .welcome-banner-text { position: relative; z-index: 1; }
        .welcome-banner-text .date { font-size: 12px; color: rgba(255,255,255,.7); margin-bottom: 8px; }
        .welcome-banner-text h2 { font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 4px; }
        .welcome-banner-text p { font-size: 13px; color: rgba(255,255,255,.75); }

        /* ── CARDS ──────────────────────────────── */
        .sp-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(124,58,237,.04);
        }

        /* ── STAT CARDS ─────────────────────────── */
        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 22px;
            position: relative;
            overflow: hidden;
            transition: border-color .2s, transform .2s, box-shadow .2s;
            box-shadow: 0 2px 8px rgba(124,58,237,.05);
        }
        .stat-card:hover { border-color: var(--purple-mid); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(124,58,237,.12); }
        .stat-icon {
            width: 46px; height: 46px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; float: right;
        }
        .stat-num { font-size: 32px; font-weight: 800; color: var(--text); line-height: 1; margin-top: 24px; }
        .stat-label { font-size: 12px; color: var(--text-soft); margin-top: 4px; text-transform: uppercase; letter-spacing: .05em; }
        .stat-trend { font-size: 11px; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
        .trend-up   { color: var(--green); }
        .trend-down { color: var(--red); }

        /* ── TABLE ──────────────────────────────── */
        .sp-table { width: 100%; border-collapse: collapse; }
        .sp-table th {
            font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
            color: var(--text-soft); font-weight: 600; padding: 10px 16px;
            border-bottom: 1.5px solid var(--border); text-align: left; background: var(--surface2);
        }
        .sp-table td { padding: 13px 16px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); vertical-align: middle; }
        .sp-table tbody tr:last-child td { border-bottom: none; }
        .sp-table tbody tr:hover td { background: var(--purple-light); }

        /* ── BADGES ─────────────────────────────── */
        .badge-sp {
            display: inline-flex; align-items: center;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-green  { background: var(--green-bg);  color: var(--green); }
        .badge-red    { background: var(--red-bg);    color: var(--red); }
        .badge-amber  { background: var(--amber-bg);  color: var(--amber); }
        .badge-blue   { background: var(--blue-bg);   color: var(--blue); }
        .badge-purple { background: var(--purple-light); color: var(--purple); }
        .badge-muted  { background: #f3f4f6; color: #6b7280; }

        [class*="badge-status-ativa"]       { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--green-bg);color:var(--green); }
        [class*="badge-status-trancada"]    { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--amber-bg);color:var(--amber); }
        [class*="badge-status-cancelada"]   { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--red-bg);color:var(--red); }
        [class*="badge-status-concluida"]   { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--blue-bg);color:var(--blue); }
        [class*="badge-status-aberta"]      { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--green-bg);color:var(--green); }
        [class*="badge-status-em_andamento"]{ display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--blue-bg);color:var(--blue); }
        [class*="badge-status-encerrada"]   { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#f3f4f6;color:#6b7280; }
        [class*="badge-status-transferida"] { display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--blue-bg);color:var(--blue); }
        [class*="badge-status-planejamento"]{ display:inline-flex;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--purple-light);color:var(--purple); }

        /* ── FORM CONTROLS ──────────────────────── */
        .form-control, .form-select {
            background: var(--surface2) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
            border-radius: 10px !important;
            font-size: 14px !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--purple-mid) !important;
            box-shadow: 0 0 0 3px rgba(124,58,237,.12) !important;
        }
        .form-control::placeholder { color: var(--text-soft) !important; }
        .form-label { color: var(--text-soft); font-size: 12px; font-weight: 600; margin-bottom: 6px; }
        .invalid-feedback { display: block; }

        /* ── BUTTONS ────────────────────────────── */
        .btn-primary { background: var(--purple) !important; border-color: var(--purple) !important; border-radius: 10px !important; font-weight: 600 !important; }
        .btn-primary:hover { background: var(--purple-dark) !important; border-color: var(--purple-dark) !important; }
        .btn-outline-secondary { border-color: var(--border) !important; color: var(--text-soft) !important; border-radius: 10px !important; background: transparent !important; }
        .btn-outline-secondary:hover { border-color: var(--purple-mid) !important; color: var(--purple) !important; background: var(--purple-light) !important; }
        .btn-outline-primary { border-color: var(--purple) !important; color: var(--purple) !important; border-radius: 10px !important; background: transparent !important; }
        .btn-outline-primary:hover { background: var(--purple-light) !important; }
        .btn-outline-danger { border-radius: 10px !important; }
        .btn-sm { font-size: 12px !important; padding: 5px 12px !important; }

        /* ── ALERTS ─────────────────────────────── */
        .alert { border-radius: 12px !important; font-size: 14px; }
        .alert-success { background: var(--green-bg) !important; border-color: #86efac !important; color: var(--green) !important; }
        .alert-danger  { background: var(--red-bg)   !important; border-color: #fca5a5 !important; color: var(--red) !important; }

        /* ── PAGINATION ─────────────────────────── */
        .pagination .page-link { background: var(--surface) !important; border-color: var(--border) !important; color: var(--text-soft) !important; border-radius: 8px !important; margin: 0 2px; }
        .pagination .page-link:hover { border-color: var(--purple-mid) !important; color: var(--purple) !important; background: var(--purple-light) !important; }
        .pagination .active .page-link { background: var(--purple) !important; border-color: var(--purple) !important; color: #fff !important; }

        /* ── SECTION LABEL ──────────────────────── */
        .section-label { font-size: 10px; text-transform: uppercase; letter-spacing: .12em; color: var(--text-soft); font-weight: 700; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1.5px solid var(--border); }
        .sp-divider { border-color: var(--border) !important; margin: 16px 0; }

        /* ── DROPDOWN ───────────────────────────── */
        .dropdown-menu { background: var(--surface) !important; border-color: var(--border) !important; border-radius: 14px !important; padding: 8px !important; box-shadow: 0 8px 30px rgba(124,58,237,.12) !important; }
        .dropdown-item { color: var(--text) !important; border-radius: 8px !important; font-size: 13px !important; }
        .dropdown-item:hover { background: var(--purple-light) !important; color: var(--purple) !important; }

        /* ── INPUT GROUP ────────────────────────── */
        .input-group-text { background: var(--surface2) !important; border-color: var(--border) !important; color: var(--text-soft) !important; }

        /* ── MOBILE ─────────────────────────────── */
        @media (max-width: 768px) {
            body { padding: 0; gap: 0; }
            .right-panel { display: none; }
            .sidebar {
                position: fixed; left: 0; top: 0; bottom: 0;
                border-radius: 0 20px 20px 0;
                z-index: 200;
                transform: translateX(-100%);
                transition: transform .25s, width .3s;
            }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 199; }
            .sidebar-overlay.show { display: block; }
            .main-wrapper { border-radius: 0; }
        }
    </style>
    @stack('styles')
</head>
<body id="appBody">

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">

    <!-- Logo + brand -->
    <div class="sidebar-header">
        <a class="sidebar-logo" href="{{ route('dashboard') }}">
            <i class="bi bi-mortarboard-fill"></i>
        </a>
        <div class="sidebar-brand">
            <span class="sidebar-brand-name">SCHOOL+</span>
            <span class="sidebar-brand-sub">Gestão Escolar</span>
        </div>
    </div>

    <!-- Nav -->
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i>
        <span class="nav-label">Dashboard</span>
    </a>

    @if(in_array(session('usuario_perfil'), ['admin','secretaria','superadmin']))

    <div class="nav-section"><span class="nav-label">Acadêmico</span></div>

    <a href="{{ route('alunos.index') }}"
       class="nav-item {{ request()->routeIs('alunos.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i>
        <span class="nav-label">Alunos</span>
    </a>
    <a href="{{ route('turmas.index') }}"
       class="nav-item {{ request()->routeIs('turmas.*') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i>
        <span class="nav-label">Turmas</span>
    </a>
    <a href="{{ route('matriculas.index') }}"
       class="nav-item {{ request()->routeIs('matriculas.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check-fill"></i>
        <span class="nav-label">Matrículas</span>
    </a>
    <a href="{{ route('cursos.index') }}"
       class="nav-item {{ request()->routeIs('cursos.*') ? 'active' : '' }}">
        <i class="bi bi-book-fill"></i>
        <span class="nav-label">Cursos</span>
    </a>
    <a href="{{ route('periodos.index') }}"
       class="nav-item {{ request()->routeIs('periodos.*') ? 'active' : '' }}">
        <i class="bi bi-calendar3"></i>
        <span class="nav-label">Períodos</span>
    </a>

    <div class="nav-section"><span class="nav-label">Diário</span></div>

    <a href="{{ route('disciplinas.index') }}"
       class="nav-item {{ request()->routeIs('disciplinas.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i>
        <span class="nav-label">Disciplinas</span>
    </a>
    <a href="{{ route('professores.index') }}"
       class="nav-item {{ request()->routeIs('professores.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge-fill"></i>
        <span class="nav-label">Professores</span>
    </a>

    <div class="nav-section"><span class="nav-label">Financeiro</span></div>

    <a href="{{ route('financeiro.index') }}"
       class="nav-item {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow"></i>
        <span class="nav-label">Dashboard</span>
    </a>
    <a href="{{ route('cobrancas.index') }}"
       class="nav-item {{ request()->routeIs('cobrancas.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>
        <span class="nav-label">Cobranças</span>
    </a>
    <a href="{{ route('planos.index') }}"
       class="nav-item {{ request()->routeIs('planos.*') ? 'active' : '' }}">
        <i class="bi bi-credit-card-fill"></i>
        <span class="nav-label">Planos</span>
    </a>
    <a href="{{ route('negociacoes.index') }}"
       class="nav-item {{ request()->routeIs('negociacoes.*') ? 'active' : '' }}">
        <i class="bi bi-handshake-fill"></i>
        <span class="nav-label">Negociações</span>
    </a>

    @endif

    <div class="sidebar-spacer"></div>

    <!-- Toggle open/close -->
    <button class="sidebar-toggle" onclick="toggleSidebar()" title="Expandir / Fechar">
        <i class="bi bi-chevron-left" id="toggleIcon"></i>
        <span class="nav-label">Fechar menu</span>
    </button>

    <!-- User / logout -->
    <div class="dropdown dropend">
        <a href="#" class="nav-item" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i>
            <span class="nav-label">{{ explode(' ', session('usuario_nome'))[0] }}</span>
        </a>
        <ul class="dropdown-menu ms-2" style="min-width:210px">
            <li>
                <span class="dropdown-item-text" style="font-size:12px;padding:6px 14px;color:var(--text-soft)">
                    {{ session('usuario_nome') }}<br>
                    <small>{{ session('usuario_perfil') }}</small>
                </span>
            </li>
            <li><hr class="dropdown-divider" style="border-color:var(--border)"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Sair do sistema
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<!-- Main -->
<div class="main-wrapper">

    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3 flex-1">
            <!-- Mobile hamburger -->
            <button class="topbar-btn d-md-none" onclick="openSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="topbar-search d-none d-md-block">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Buscar...">
            </div>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-btn">
                <i class="bi bi-bell"></i>
            </a>
            <div class="topbar-user">
                <div class="topbar-user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="d-none d-sm-block">
                    <div class="topbar-user-name">{{ explode(' ', session('usuario_nome'))[0] }}</div>
                    <div class="topbar-user-role">{{ ucfirst(session('usuario_perfil')) }}</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="content-area">
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        @hasSection('right-panel')
        <aside class="right-panel">
            @yield('right-panel')
        </aside>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Restore sidebar state
    const body = document.getElementById('appBody');
    if (localStorage.getItem('sbClosed') === '1') body.classList.add('sb-closed');

    function toggleSidebar() {
        body.classList.toggle('sb-closed');
        localStorage.setItem('sbClosed', body.classList.contains('sb-closed') ? '1' : '0');
    }

    // Mobile overlay
    function openSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
</script>
@stack('scripts')
</body>
</html>
