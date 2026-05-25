<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno — School+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #1a0938;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* Floating dots */
        .dot {
            position: fixed;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            pointer-events: none;
        }
        .dot-1 { width: 18px; height: 18px; top: 8%;  left: 6%;  }
        .dot-2 { width: 10px; height: 10px; top: 14%; left: 42%; opacity:.6; }
        .dot-3 { width: 14px; height: 14px; top: 72%; left: 3%;  }
        .dot-4 { width: 22px; height: 22px; top: 80%; right: 5%; opacity:.5; }
        .dot-5 { width: 10px; height: 10px; top: 20%; right: 10%;opacity:.4; }
        .dot-6 { width: 16px; height: 16px; top: 50%; right: 2%; opacity:.55;}

        /* ── OUTER CARD ─────────────────────────── */
        .login-card {
            display: flex;
            width: min(900px, 96vw);
            min-height: 520px;
            background: #fff;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 80px rgba(0,0,0,.5), 0 4px 20px rgba(0,0,0,.2);
            position: relative;
            z-index: 1;
        }

        /* ── LEFT PANEL ─────────────────────────── */
        .left-panel {
            width: 44%;
            border-radius: 28px;
            margin: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
            background: linear-gradient(155deg, #4c1d95 0%, #3b0764 40%, #2e0556 100%);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.12), transparent 65%);
            top: -60px; left: -60px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.08), transparent 65%);
            bottom: -40px; right: -40px;
        }

        .left-brand {
            position: absolute;
            top: 20px; left: 20px;
            display: flex; align-items: center; gap: 8px;
            z-index: 2;
        }
        .left-brand .logo-box {
            width: 34px; height: 34px;
            background: rgba(255,255,255,.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(8px);
        }
        .left-brand .logo-box i { color: #fff; font-size: 16px; }
        .left-brand span { color: #fff; font-size: 14px; font-weight: 800; letter-spacing: .02em; }

        .illus-wrap {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px 24px 28px;
        }

        .illus-base { position: relative; }
        .illus-base::after {
            content: '';
            position: absolute;
            bottom: 8px; left: 50%;
            transform: translateX(-50%);
            width: 130px; height: 20px;
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            filter: blur(6px);
        }

        .left-tagline { text-align: center; z-index: 2; position: relative; }
        .left-tagline h3 {
            color: #fff;
            font-size: 17px; font-weight: 800;
            margin-bottom: 4px; line-height: 1.3;
        }
        .left-tagline p { color: rgba(255,255,255,.65); font-size: 12px; }

        /* ── RIGHT PANEL ────────────────────────── */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 44px;
            background: #fff;
        }

        .right-header { margin-bottom: 32px; }
        .right-header h1 {
            font-size: 28px; font-weight: 900;
            color: #1e1b4b; margin-bottom: 6px; letter-spacing: -.02em;
        }
        .right-header p { color: #9ca3af; font-size: 14px; }

        /* Inputs */
        .field { margin-bottom: 16px; }
        .field input {
            width: 100%;
            padding: 16px 20px;
            background: #fff;
            border: none;
            border-radius: 14px;
            color: #1e1b4b;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 12px rgba(76,29,149,.12), 0 0 0 1.5px #ddd6fe;
            transition: box-shadow .2s;
        }
        .field input::placeholder { color: #a78bfa; }
        .field input:focus {
            box-shadow: 0 4px 20px rgba(76,29,149,.22), 0 0 0 2px #5b21b6;
        }
        .field input.is-invalid {
            box-shadow: 0 2px 12px rgba(239,68,68,.1), 0 0 0 1.5px #fca5a5;
        }
        .field-pw { position: relative; }
        .field-pw input { padding-right: 52px; }
        .field-toggle {
            position: absolute; right: 16px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #a78bfa; cursor: pointer; font-size: 16px;
            padding: 4px; transition: color .15s;
        }
        .field-toggle:hover { color: #4c1d95; }

        .invalid-msg {
            color: #ef4444; font-size: 12px;
            margin-top: 6px;
            display: flex; align-items: center; gap: 4px;
        }

        .forgot-row { text-align: right; margin-bottom: 24px; }
        .forgot-row a { font-size: 12px; color: #9ca3af; text-decoration: none; }
        .forgot-row a:hover { color: #4c1d95; }

        .btn-signin {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4c1d95, #7c3aed);
            border: none; border-radius: 14px;
            color: #fff; font-size: 15px; font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 8px 28px rgba(76,29,149,.45);
            letter-spacing: .01em;
        }
        .btn-signin:hover {
            opacity: .92; transform: translateY(-1px);
            box-shadow: 0 12px 36px rgba(76,29,149,.55);
        }
        .btn-signin:active { transform: translateY(0); }

        .login-footer { margin-top: 28px; text-align: center; }
        .login-footer p { color: #d1d5db; font-size: 11px; }
        .login-footer a { color: #7c3aed; text-decoration: none; font-weight: 600; }
        .login-footer a:hover { color: #4c1d95; }

        @media (max-width: 600px) {
            .left-panel { display: none; }
            .login-card { width: 95vw; min-height: auto; }
            .right-panel { padding: 36px 24px; }
        }
    </style>
</head>
<body>

<div class="dot dot-1"></div>
<div class="dot dot-2"></div>
<div class="dot dot-3"></div>
<div class="dot dot-4"></div>
<div class="dot dot-5"></div>
<div class="dot dot-6"></div>

<div class="login-card">

    <!-- LEFT -->
    <div class="left-panel">
        <div class="left-brand">
            <div class="logo-box"><i class="bi bi-mortarboard-fill"></i></div>
            <span>SCHOOL+</span>
        </div>

        <div class="illus-wrap">
            <div class="illus-base">
                <svg width="200" height="210" viewBox="0 0 200 210" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="195" rx="64" ry="10" fill="rgba(0,0,0,.18)"/>
                    <rect x="38" y="130" width="124" height="56" rx="22" fill="#f9c74f"/>
                    <rect x="50" y="90" width="100" height="70" rx="20" fill="#f4a226"/>
                    <rect x="58" y="96" width="36" height="58" rx="14" fill="#f9c74f"/>
                    <rect x="28" y="118" width="28" height="60" rx="14" fill="#f4a226"/>
                    <rect x="144" y="118" width="28" height="60" rx="14" fill="#f4a226"/>
                    <rect x="52" y="178" width="10" height="20" rx="5" fill="#e07b10"/>
                    <rect x="138" y="178" width="10" height="20" rx="5" fill="#e07b10"/>
                    <ellipse cx="98" cy="112" rx="22" ry="28" fill="#56cfad"/>
                    <circle cx="98" cy="72" r="22" fill="#ffb8a0"/>
                    <ellipse cx="98" cy="60" rx="22" ry="16" fill="#ff6b8a"/>
                    <ellipse cx="82" cy="78" rx="8" ry="18" fill="#ff6b8a"/>
                    <ellipse cx="114" cy="80" rx="8" ry="16" fill="#ff6b8a"/>
                    <circle cx="98" cy="49" r="11" fill="#ff6b8a"/>
                    <circle cx="91" cy="72" r="3" fill="#1e1b4b"/>
                    <circle cx="105" cy="72" r="3" fill="#1e1b4b"/>
                    <circle cx="92" cy="71" r="1" fill="#fff"/>
                    <circle cx="106" cy="71" r="1" fill="#fff"/>
                    <ellipse cx="87" cy="77" rx="5" ry="3" fill="rgba(255,120,120,.3)"/>
                    <ellipse cx="109" cy="77" rx="5" ry="3" fill="rgba(255,120,120,.3)"/>
                    <path d="M93 80 Q98 85 103 80" stroke="#c0392b" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    <ellipse cx="130" cy="134" rx="12" ry="28" transform="rotate(-20 130 134)" fill="#f5e6c8"/>
                    <ellipse cx="148" cy="128" rx="10" ry="24" transform="rotate(-30 148 128)" fill="#f5e6c8"/>
                    <ellipse cx="155" cy="107" rx="10" ry="7" transform="rotate(-20 155 107)" fill="#2d2d3a"/>
                    <ellipse cx="166" cy="103" rx="9" ry="6" transform="rotate(-30 166 103)" fill="#2d2d3a"/>
                    <ellipse cx="153" cy="104" rx="5" ry="3" transform="rotate(-20 153 104)" fill="#fff" opacity=".4"/>
                    <rect x="104" y="100" width="32" height="42" rx="6" fill="#2d2d3a" transform="rotate(10 104 100)"/>
                    <rect x="107" y="103" width="26" height="34" rx="4" fill="#6366f1" transform="rotate(10 107 103)"/>
                    <rect x="110" y="108" width="18" height="2.5" rx="1.5" fill="rgba(255,255,255,.5)" transform="rotate(10 110 108)"/>
                    <rect x="110" y="113" width="13" height="2" rx="1" fill="rgba(255,255,255,.3)" transform="rotate(10 110 113)"/>
                    <rect x="110" y="118" width="16" height="2" rx="1" fill="rgba(255,255,255,.3)" transform="rotate(10 110 118)"/>
                    <rect x="154" y="60" width="6" height="70" rx="3" fill="#e8d5b7"/>
                    <ellipse cx="157" cy="58" rx="20" ry="12" fill="#fff9e0" opacity=".9"/>
                    <ellipse cx="157" cy="58" rx="20" ry="6" fill="#f5e96a" opacity=".5"/>
                    <rect x="20" y="165" width="18" height="24" rx="5" fill="#d4a25a"/>
                    <ellipse cx="29" cy="162" rx="14" ry="16" fill="#4ade80"/>
                    <ellipse cx="20" cy="158" rx="9" ry="11" fill="#22c55e"/>
                    <ellipse cx="38" cy="158" rx="9" ry="11" fill="#22c55e"/>
                    <rect x="150" y="160" width="20" height="28" rx="6" fill="#c8956c"/>
                    <ellipse cx="160" cy="152" rx="16" ry="20" fill="#4ade80"/>
                    <ellipse cx="148" cy="148" rx="10" ry="13" fill="#22c55e"/>
                    <ellipse cx="172" cy="148" rx="10" ry="13" fill="#22c55e"/>
                </svg>
            </div>

            <div class="left-tagline">
                <h3>Seu espaço de<br>aprendizado</h3>
                <p>Notas, frequência e muito mais</p>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
        <div class="right-header">
            <h1>Portal do Aluno</h1>
            <p>Entre com suas credenciais para acessar o portal.</p>
        </div>

        <form method="POST" action="{{ route('portal.login.post') }}">
            @csrf

            <div class="field">
                <input type="email" name="email"
                       placeholder="Seu e-mail"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                       autofocus>
                @error('email')
                    <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="field field-pw">
                <input type="password" name="password" id="pwdInput"
                       placeholder="Senha"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" class="field-toggle" onclick="togglePwd()" id="pwdToggle">
                    <i class="bi bi-eye-slash" id="pwdIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="forgot-row">
                <a href="#">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn-signin">
                Entrar no Portal
            </button>
        </form>

        <div class="login-footer">
            <p>School+ &copy; {{ date('Y') }} &mdash; Sistema de Gestão Educacional</p>
            <p style="margin-top:8px"><a href="{{ route('login') }}">Acesso administrativo →</a></p>
        </div>
    </div>

</div>

<script>
function togglePwd() {
    const input = document.getElementById('pwdInput');
    const icon  = document.getElementById('pwdIcon');
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    icon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
<style>
#sp-bar{position:fixed;top:0;left:0;right:0;height:3px;z-index:99999;border-radius:0 2px 2px 0;transform-origin:left;transform:scaleX(0);opacity:0;pointer-events:none;background:linear-gradient(90deg,#2e0556,#7c3aed,#2e0556);background-size:200% 100%;box-shadow:0 0 10px rgba(124,58,237,.5);}
#sp-bar.sp-active{opacity:1;animation:sp-bar-s 1.1s ease-in-out infinite alternate,sp-bar-sh 1.1s ease-in-out infinite;}
#sp-bar.sp-done{animation:none;transform:scaleX(1);transition:transform .12s,opacity .25s .12s;opacity:0;}
@keyframes sp-bar-s{from{transform:scaleX(.15)}to{transform:scaleX(.82)}}
@keyframes sp-bar-sh{0%{background-position:200% 0}100%{background-position:-200% 0}}
@keyframes sp-spin{to{transform:rotate(360deg)}}
.sp-spin{display:inline-block;width:16px;height:16px;border:2.5px solid currentColor;border-right-color:transparent;border-radius:50%;animation:sp-spin .65s linear infinite;vertical-align:middle;}
</style>
<div id="sp-bar"></div>
<script>
(function(){
    const bar=document.getElementById('sp-bar');
    let t;
    function start(){bar.classList.remove('sp-done');bar.classList.add('sp-active');}
    function stop(){bar.classList.remove('sp-active');bar.classList.add('sp-done');t=setTimeout(()=>{bar.classList.remove('sp-done');bar.style.transform='scaleX(0)'},450);}
    document.addEventListener('submit',function(e){
        start();
        const btn=e.target.querySelector('[type="submit"]');
        if(btn&&!btn.disabled){btn.disabled=true;btn.innerHTML='<span class="sp-spin" style="border-color:rgba(255,255,255,.3);border-top-color:#fff;width:14px;height:14px;border-width:2px"></span><span style="margin-left:8px">Entrando...</span>';}
    });
    window.addEventListener('load',stop);
    window.addEventListener('pageshow',function(e){if(e.persisted)stop();});
})();
</script>
</body>
</html>
