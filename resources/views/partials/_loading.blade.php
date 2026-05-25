{{-- ─────────────────────────────────────────────────────────────────────────
     _loading.blade.php — global loading system
     ─────────────────────────────────────────────────────────────────────────── --}}
<style>
/* ── TOP BAR ──────────────────────────────────────────────────────── */
#sp-bar {
    position: fixed; top: 0; left: 0; right: 0; height: 3px;
    z-index: 99999; pointer-events: none;
    transform-origin: left; transform: scaleX(0); opacity: 0;
    border-radius: 0 2px 2px 0;
    background: linear-gradient(90deg,#7c3aed,#a78bfa,#7c3aed);
    background-size: 200% 100%;
    box-shadow: 0 0 10px rgba(124,58,237,.5);
}
#sp-bar.sp-active {
    opacity: 1;
    animation: sp-bar-s 1.1s ease-in-out infinite alternate,
               sp-bar-sh 1.4s ease-in-out infinite;
}
#sp-bar.sp-done {
    animation: none !important;
    transform: scaleX(1) !important;
    transition: transform .12s ease, opacity .3s .1s ease;
    opacity: 0 !important;
}
@keyframes sp-bar-s  { from{transform:scaleX(.12)} to{transform:scaleX(.78)} }
@keyframes sp-bar-sh { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

/* ── SKELETON / SHIMMER UTILITY ──────────────────────────────────── */
@keyframes sp-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.skeleton, .sk {
    background: linear-gradient(90deg,#ede9fe 25%,#ddd6fe 50%,#ede9fe 75%) !important;
    background-size: 200% 100% !important;
    animation: sp-shimmer 1.5s ease-in-out infinite !important;
    border-radius: 8px !important; color: transparent !important;
    pointer-events: none; user-select: none;
}
.sk-r4  { border-radius: 4px  !important; }
.sk-r12 { border-radius: 12px !important; }
.sk-r50 { border-radius: 50%  !important; }

/* ── PAGE LOADER OVERLAY ─────────────────────────────────────────────
   Position is set via JS (positionLoader) so it always aligns with
   .main-wrapper regardless of sidebar width / padding changes.       */
#sp-loader {
    display: none;          /* invisible until sp-active */
    position: fixed;
    z-index: 9990;
    /* top/left/right/bottom set dynamically by JS */
    border-radius: 20px; overflow: hidden;
    background: rgba(248,247,255,.94);
    backdrop-filter: blur(4px);
    flex-direction: column; gap: 14px; padding: 28px;
    pointer-events: none;
}
#sp-loader.sp-active {
    display: flex;
    pointer-events: all;
    animation: sp-fadein .18s ease forwards;
}
@keyframes sp-fadein { from{opacity:0} to{opacity:1} }

/* Skeleton card */
.sp-sk-card {
    background:#fff; border:1px solid #e5e0f5; border-radius:18px;
    padding:20px 22px; display:flex; flex-direction:column; gap:10px; flex-shrink:0;
}
.sp-sk-row { display:flex; gap:10px; align-items:center; }
.sp-sk-blk {
    flex-shrink:0;
    background: linear-gradient(90deg,#ede9fe 25%,#ddd6fe 50%,#ede9fe 75%);
    background-size:200% 100%;
    animation: sp-shimmer 1.5s ease-in-out infinite;
    border-radius:8px;
}

/* ── SPINNER ─────────────────────────────────────────────────────── */
@keyframes sp-spin { to{transform:rotate(360deg)} }
.sp-spin {
    display:inline-block; width:16px; height:16px;
    border:2.5px solid currentColor; border-right-color:transparent;
    border-radius:50%; animation:sp-spin .65s linear infinite;
    vertical-align:middle; flex-shrink:0;
}
.sp-spin-lg  { width:32px; height:32px; border-width:3px; }
.sp-spin-xl  { width:48px; height:48px; border-width:4px; }
.sp-spin-purple { border-color:rgba(124,58,237,.2); border-top-color:#7c3aed; }

.sp-loading-center {
    display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:14px; padding:48px; color:#7c3aed;
}
.sp-loading-center .sp-spin { width:36px; height:36px; border-width:3px; }
.sp-loading-center span { font-size:13px; font-weight:600; opacity:.7; }

@media (max-width:768px) {
    #sp-loader { left:0; bottom:0; top:64px; border-radius:0; }
}
</style>

<div id="sp-bar"></div>

<div id="sp-loader" aria-hidden="true">
    <div class="sp-sk-card" style="flex:0 0 auto">
        <div class="sp-sk-row">
            <div class="sp-sk-blk" style="width:42px;height:42px;border-radius:12px"></div>
            <div style="flex:1;display:flex;flex-direction:column;gap:6px">
                <div class="sp-sk-blk" style="height:16px;width:52%"></div>
                <div class="sp-sk-blk" style="height:11px;width:32%"></div>
            </div>
            <div class="sp-sk-blk" style="width:88px;height:30px;border-radius:8px"></div>
        </div>
    </div>
    <div class="sp-sk-card" style="flex:1">
        <div class="sp-sk-blk" style="height:11px;width:28%;border-radius:4px;margin-bottom:4px"></div>
        @for($i = 0; $i < 5; $i++)
        <div class="sp-sk-row" style="border-bottom:1px solid #f3f0fe;padding-bottom:10px">
            <div class="sp-sk-blk" style="width:34px;height:34px;border-radius:50%"></div>
            <div style="flex:1;display:flex;flex-direction:column;gap:5px">
                <div class="sp-sk-blk" style="height:13px;width:{{ 55 + ($i * 6) % 28 }}%"></div>
                <div class="sp-sk-blk" style="height:10px;width:{{ 28 + ($i * 8) % 22 }}%"></div>
            </div>
            <div class="sp-sk-blk" style="width:58px;height:22px;border-radius:20px"></div>
        </div>
        @endfor
    </div>
</div>

<script>
(function () {
    var bar    = document.getElementById('sp-bar');
    var loader = document.getElementById('sp-loader');
    var doneTimer, loaderTimer, safeTimer;

    // ── align loader with .main-wrapper bounding box ─────────────────
    function positionLoader() {
        var target = document.querySelector('.main-wrapper');
        if (!target) return;
        var r = target.getBoundingClientRect();
        loader.style.top    = Math.round(r.top)    + 'px';
        loader.style.left   = Math.round(r.left)   + 'px';
        loader.style.right  = Math.round(window.innerWidth  - r.right)  + 'px';
        loader.style.bottom = Math.round(window.innerHeight - r.bottom) + 'px';
    }

    // ── show ─────────────────────────────────────────────────────────
    function startLoad() {
        clearTimeout(doneTimer);
        clearTimeout(loaderTimer);
        clearTimeout(safeTimer);

        bar.classList.remove('sp-done');
        bar.classList.add('sp-active');

        // skeleton only after 150ms — no flash on fast navigations
        loaderTimer = setTimeout(function () {
            positionLoader();
            loader.classList.add('sp-active');
        }, 150);

        // hard stop after 8s
        safeTimer = setTimeout(stopLoad, 8000);
    }

    // ── hide ─────────────────────────────────────────────────────────
    function stopLoad() {
        clearTimeout(loaderTimer);
        clearTimeout(safeTimer);

        // Only touch loader if it is actually showing
        if (loader.classList.contains('sp-active')) {
            loader.classList.remove('sp-active'); // display:none restores immediately
        }

        bar.classList.remove('sp-active');
        bar.classList.add('sp-done');
        doneTimer = setTimeout(function () {
            bar.classList.remove('sp-done');
        }, 500);
    }

    // ── link clicks ──────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a[href]');
        if (!a) return;
        if (a.getAttribute('data-no-loading') !== null) return;
        if (a.getAttribute('data-bs-toggle')  !== null) return;
        if (a.getAttribute('data-bs-dismiss') !== null) return;
        if (a.getAttribute('download')        !== null) return;
        if (a.target === '_blank') return;
        var href = a.getAttribute('href') || '';
        if (!href || href.charAt(0) === '#'
            || href.indexOf('mailto:') === 0
            || href.indexOf('tel:') === 0
            || href.indexOf('javascript:') === 0) return;
        try {
            var u = new URL(href, location.href);
            if (u.hostname !== location.hostname) return;
        } catch(x) {}
        startLoad();
    });

    // ── form submits ─────────────────────────────────────────────────
    document.addEventListener('submit', function (e) {
        if (e.target.getAttribute('data-no-loading') !== null) return;
        startLoad();
        var btn = e.target.querySelector('[type="submit"]:not([data-no-spin])');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            var label = (btn.textContent || '').trim();
            label = label.length > 22 ? 'Aguarde...' : label;
            btn.innerHTML = '<span class="sp-spin" style="width:13px;height:13px;border-width:2px;'
                + 'border-color:rgba(255,255,255,.35);border-top-color:#fff;vertical-align:middle"></span>'
                + (label ? '<span style="margin-left:7px;vertical-align:middle">' + label + '</span>' : '');
        }
    });

    // ── bfcache restore ──────────────────────────────────────────────
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) stopLoad();
    });

    // ── page load — always stop, guard is inside stopLoad ────────────
    function onReady() { stopLoad(); }
    if (document.readyState === 'complete') {
        onReady();
    } else {
        window.addEventListener('load', onReady);
    }
})();
</script>
