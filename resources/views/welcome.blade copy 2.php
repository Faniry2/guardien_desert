<!DOCTYPE html>
<html lang="fr" data-mode="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renait-Sens — L'Ombre du Tassili</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital@0;1&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        /* ══ PALETTES 3 MODES ══ */
        [data-mode="night"] {
            --sky-top:#03030F; --sky-mid:#0A0820; --sky-horizon:#1A1230;
            --ground-top:#1A1208; --ground-bot:#0A0A12;
            --crest:rgba(201,169,110,0.25);
            --txt:#F5E6C8; --sub:#C9A96E; --acc:#D4622A; --acc-glow:rgba(212,98,42,0.45);
            --btn-brd:rgba(201,169,110,0.35); --mod-bg:rgba(10,10,18,0.72);
            --mod-hov:rgba(201,169,110,0.07); --ban-bg:rgba(13,13,26,0.65);
            --h1glow:rgba(201,169,110,0.4);
        }
        [data-mode="dawn"] {
            --sky-top:#1A0E22; --sky-mid:#5C2D52; --sky-horizon:#E8845A;
            --ground-top:#7A4A22; --ground-bot:#3D2010;
            --crest:rgba(255,180,100,0.42);
            --txt:#FFF0D8; --sub:#F0B870; --acc:#E05A20; --acc-glow:rgba(224,90,32,0.45);
            --btn-brd:rgba(240,184,112,0.4); --mod-bg:rgba(30,15,10,0.65);
            --mod-hov:rgba(240,184,112,0.09); --ban-bg:rgba(25,12,8,0.6);
            --h1glow:rgba(255,160,80,0.45);
        }
        [data-mode="noon"] {
            --sky-top:#4A8FD4; --sky-mid:#82BBE8; --sky-horizon:#C8E0F0;
            --ground-top:#C8A050; --ground-bot:#8A6820;
            --crest:rgba(255,220,130,0.55);
            --txt:#FFF8EC; --sub:#FFD98A; --acc:#E06010; --acc-glow:rgba(224,96,16,0.5);
            --btn-brd:rgba(255,220,140,0.5); --mod-bg:rgba(20,12,0,0.58);
            --mod-hov:rgba(255,200,80,0.15); --ban-bg:rgba(15,8,0,0.62);
            --h1glow:rgba(255,200,80,0.55);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cormorant Garamond', Georgia, serif;
            overflow-x: hidden;
            min-height: 100vh;
            color: var(--txt);
            transition: color 1.4s;
        }

        /* SKY */
        #sky-bg {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(180deg, var(--sky-top) 0%, var(--sky-mid) 45%, var(--sky-horizon) 100%);
            transition: background 2s ease;
        }

        /* GRAIN */
        .grain {
            position: fixed; inset: 0; z-index: 1; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.28;
        }

        /* CANVAS */
        canvas {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
        }
        #sky-canvas { z-index: 2; }
        #bird-canvas { z-index: 3; opacity: 0; transition: opacity 2s; }
        [data-mode="dawn"] #bird-canvas { opacity: 1; }

        /* HEAT SHIMMER */
        #heat {
            position: fixed; bottom: 28vh; left: 0;
            width: 100%; height: 130px; z-index: 3;
            pointer-events: none; opacity: 0; transition: opacity 1.5s;
            background: linear-gradient(0deg, rgba(200,160,80,0.12), transparent);
            animation: shimmer 2.8s ease-in-out infinite;
        }
        [data-mode="noon"] #heat { opacity: 1; }
        @keyframes shimmer { 0%,100%{transform:scaleY(1)} 50%{transform:scaleY(1.09) translateY(-5px)} }

        /* DUNES */
        .dunes {
            position: fixed; bottom: 0; left: 0;
            width: 100%; height: 50vh; z-index: 4; pointer-events: none;
            transition: transform 0.15s ease-out;
        }
        .dunes svg { width: 100%; height: 100%; }

        /* MODE SWITCHER */
        .switcher {
            position: fixed; top: 1.6rem; right: 1.8rem; z-index: 100;
            display: flex; gap: 0.4rem;
            background: rgba(0,0,0,0.28);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50px; padding: 0.35rem;
        }
        .sw-btn {
            display: flex; align-items: center; gap: 0.35rem;
            padding: 0.4rem 0.9rem; border-radius: 50px; border: none;
            background: transparent; color: rgba(255,255,255,0.45);
            font-family: 'Philosopher', serif; font-size: 0.7rem;
            letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer;
            transition: all 0.35s;
        }
        .sw-btn.on { background: rgba(255,255,255,0.15); color: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
        [data-mode="noon"] .sw-btn { color: rgba(40,20,0,0.45); }
        [data-mode="noon"] .sw-btn.on { color: #2A1A06; background: rgba(255,255,255,0.45); }

        /* MAIN */
        main {
            position: relative; z-index: 10; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 2rem;
        }

        /* HERO */
        .hero {
            text-align: center; max-width: 860px;
            padding: 5rem 2rem 2rem;
            animation: up 1.8s cubic-bezier(0.16,1,0.3,1) both;
        }

        /* SIGIL */
        .sigil {
            display: inline-block; width: 88px; height: 88px;
            margin-bottom: 2.4rem;
            animation: spin 30s linear infinite, fadein 2s both;
        }

        .eyebrow {
            font-family: 'Philosopher', serif; font-size: 0.77rem;
            letter-spacing: 0.45em; text-transform: uppercase;
            color: var(--sub); opacity: 0.7; margin-bottom: 1.4rem;
            animation: up 2s 0.3s both; transition: color 1.4s;
        }

        h1 {
            font-family: 'Cinzel Decorative', serif;
            font-size: clamp(2rem, 5.5vw, 4.2rem);
            font-weight: 700; line-height: 1.1;
            color: var(--txt);
            text-shadow: 0 0 60px var(--h1glow), 0 2px 6px rgba(0,0,0,0.35);
            margin-bottom: 0.4rem;
            animation: up 2s 0.5s both; transition: color 1.4s, text-shadow 1.4s;
        }
        h1 em {
            display: block; font-style: italic; font-size: 0.52em;
            font-family: 'Cormorant Garamond', serif; font-weight: 300;
            color: var(--sub); letter-spacing: 0.22em; margin-top: 0.5rem;
            transition: color 1.4s;
        }

        .divider {
            display: flex; align-items: center; gap: 1.2rem;
            margin: 2.2rem auto; width: fit-content;
            animation: fadein 2s 0.9s both;
        }
        .div-line {
            width: 80px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--sub), transparent);
            transition: background 1.4s;
        }
        .div-icon { font-size: 1.4rem; animation: glow 4s ease-in-out infinite; }

        .tagline {
            font-size: clamp(1.05rem, 2.4vw, 1.42rem);
            font-weight: 300; font-style: italic; color: var(--txt);
            opacity: 0.83; line-height: 1.78; max-width: 580px;
            margin: 0 auto 3rem;
            animation: up 2s 1s both; transition: color 1.4s;
        }
        .tl { display: none; }
        [data-mode="night"] .tl-night,
        [data-mode="dawn"]  .tl-dawn,
        [data-mode="noon"]  .tl-noon { display: block; }

        /* CTA */
        .cta-group {
            display: flex; gap: 1.2rem; flex-wrap: wrap; justify-content: center;
            animation: up 2s 1.3s both;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 0.9rem 2.2rem; border-radius: 2px;
            font-family: 'Philosopher', serif; font-size: 0.88rem;
            letter-spacing: 0.15em; text-transform: uppercase;
            text-decoration: none; cursor: pointer; border: none;
            position: relative; overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s, background 1.4s, border-color 1.4s;
        }
        .btn::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0; transition: opacity 0.3s;
        }
        .btn:hover::before { opacity: 1; }
        .btn-p {
            background: linear-gradient(135deg, var(--acc), color-mix(in srgb, var(--acc) 65%, #000));
            color: #FFF5E0;
            box-shadow: 0 4px 28px var(--acc-glow), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .btn-p:hover { transform: translateY(-2px); box-shadow: 0 8px 38px var(--acc-glow); }
        .btn-s {
            background: transparent; color: var(--sub);
            border: 1px solid var(--btn-brd);
        }
        .btn-s:hover { background: rgba(255,220,140,0.1); border-color: var(--sub); transform: translateY(-2px); }

        /* MODULES */
        .modules-section {
            position: relative; z-index: 10; width: 100%; max-width: 1100px;
            margin: 5rem auto 2rem; padding: 0 2rem;
            animation: up 2s 1.6s both;
        }
        .mod-label {
            text-align: center; font-family: 'Philosopher', serif;
            font-size: 0.73rem; letter-spacing: 0.5em; text-transform: uppercase;
            color: var(--sub); opacity: 0.55; margin-bottom: 2rem;
            transition: color 1.4s;
        }
        .mod-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(138px,1fr));
            gap: 1px; background: rgba(128,100,50,0.14);
            border: 1px solid rgba(128,100,50,0.14);
        }
        .mod-card {
            background: var(--mod-bg); padding: 1.5rem 1rem; text-align: center;
            backdrop-filter: blur(10px); cursor: default; position: relative;
            transition: background 0.4s 0s, --mod-bg 1.4s;
        }
        .mod-card::after {
            content: ''; position: absolute; bottom: 0; left: 50%;
            transform: translateX(-50%); width: 0; height: 2px;
            background: var(--acc); transition: width 0.4s;
        }
        .mod-card:hover { background: var(--mod-hov); }
        .mod-card:hover::after { width: 60%; }
        .mod-num {
            font-family: 'Cinzel Decorative', serif; font-size: 0.61rem;
            color: var(--acc); letter-spacing: 0.2em; display: block;
            margin-bottom: 0.5rem; transition: color 1.4s;
        }
        .mod-name {
            font-size: 1rem; font-style: italic; color: var(--txt);
            font-weight: 300; transition: color 1.4s;
        }

        /* PACTE */
        .pacte {
            position: relative; z-index: 10; max-width: 680px;
            margin: 4rem auto 7rem; padding: 2.5rem 3rem;
            border: 1px solid rgba(128,100,50,0.22);
            background: var(--ban-bg); backdrop-filter: blur(14px);
            text-align: center; animation: fadein 2.5s 2s both;
            transition: background 1.4s;
        }
        .pacte::before, .pacte::after {
            content: ''; position: absolute;
            width: 34px; height: 34px;
            border-color: var(--sub); border-style: solid; opacity: 0.45;
            transition: border-color 1.4s;
        }
        .pacte::before { top:-1px; left:-1px; border-width: 2px 0 0 2px; }
        .pacte::after  { bottom:-1px; right:-1px; border-width: 0 2px 2px 0; }
        .pacte-title {
            font-family: 'Cinzel Decorative', serif; font-size: 0.77rem;
            letter-spacing: 0.3em; color: var(--sub); text-transform: uppercase;
            margin-bottom: 1.1rem; opacity: 0.75; transition: color 1.4s;
        }
        .pacte-txt {
            font-size: 1.1rem; font-style: italic; font-weight: 300;
            color: var(--txt); line-height: 1.88; opacity: 0.9; transition: color 1.4s;
        }
        .pacte-emoji { font-size: 1.5rem; display: block; margin-top: 1.2rem; animation: glow 3.5s ease-in-out infinite; }

        /* PARTICLES */
        .embers, .dust {
            position: fixed; pointer-events: none; z-index: 6;
            transition: opacity 1.5s;
        }
        .embers {
            bottom: 32vh; left: 50%; transform: translateX(-50%);
            opacity: 0;
        }
        [data-mode="night"] .embers { opacity: 1; }

        .dust { bottom: 26vh; left: 0; width: 100%; height: 200px; opacity: 0; }
        [data-mode="noon"] .dust { opacity: 1; }

        .ember {
            position: absolute; border-radius: 50%;
            animation: ember-rise var(--d) var(--dl) ease-out infinite; opacity: 0;
        }
        .dp {
            position: absolute; background: rgba(200,160,80,0.32); border-radius: 50%;
            animation: dust-drift var(--d) var(--dl) linear infinite;
        }

        /* SCROLL */
        .scroll-hint {
            position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%);
            z-index: 20; display: flex; flex-direction: column; align-items: center;
            gap: 0.5rem; opacity: 0.38;
            animation: fadein 3s 2.5s both, float 3s ease-in-out infinite;
            font-family: 'Philosopher', serif; font-size: 0.67rem;
            letter-spacing: 0.3em; color: var(--sub); text-transform: uppercase;
            transition: color 1.4s;
        }
        .scroll-arrow {
            width: 17px; height: 17px;
            border-right: 1px solid var(--sub); border-bottom: 1px solid var(--sub);
            transform: rotate(45deg); transition: border-color 1.4s;
        }

        /* KEYFRAMES */
        @keyframes up    { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:none} }
        @keyframes fadein{ from{opacity:0} to{opacity:1} }
        @keyframes spin  { to{transform:rotate(360deg)} }
        @keyframes glow  { 0%,100%{opacity:.75} 50%{opacity:1} }
        @keyframes ember-rise {
            0%{transform:translateY(0) translateX(0) scale(1);opacity:.9}
            60%{opacity:.35}
            100%{transform:translateY(-140px) translateX(var(--dr,20px)) scale(.1);opacity:0}
        }
        @keyframes dust-drift {
            0%{transform:translateX(-40px) translateY(0);opacity:0}
            10%{opacity:.55}
            90%{opacity:.25}
            100%{transform:translateX(calc(100vw + 40px)) translateY(var(--dy,0));opacity:0}
        }
        @keyframes float {
            0%,100%{transform:translateX(-50%) translateY(0)}
            50%{transform:translateX(-50%) translateY(-6px)}
        }

        @media(max-width:640px){
            .switcher{top:.8rem;right:.8rem}
            .sw-btn{padding:.32rem .65rem;font-size:.63rem}
            .mod-grid{grid-template-columns:repeat(2,1fr)}
            .pacte{padding:1.8rem 1.3rem;margin-bottom:5rem}
            .cta-group{flex-direction:column;align-items:center}
        }
    </style>
</head>
<body>

<div id="sky-bg"></div>
<div class="grain"></div>
<canvas id="sky-canvas"></canvas>
<canvas id="bird-canvas"></canvas>
<div id="heat"></div>

<!-- Dunes -->
<div class="dunes" id="dunes">
    <svg viewBox="0 0 1440 400" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="dg1" x1="0" y1="0" x2="0" y2="1">
                <stop id="d1a" offset="0%" stop-color="#1A1208" stop-opacity=".9"/>
                <stop id="d1b" offset="100%" stop-color="#0A0A12"/>
            </linearGradient>
            <linearGradient id="dg2" x1="0" y1="0" x2="0" y2="1">
                <stop id="d2a" offset="0%" stop-color="#241A0A" stop-opacity=".8"/>
                <stop id="d2b" offset="100%" stop-color="#0D0D1A"/>
            </linearGradient>
            <filter id="db"><feGaussianBlur stdDeviation="2"/></filter>
        </defs>
        <path d="M0,280 Q200,180 400,240 Q600,300 800,210 Q1000,120 1200,200 Q1380,270 1440,230 L1440,400 L0,400Z"
              fill="url(#dg1)" opacity=".5" filter="url(#db)"/>
        <path d="M0,318 Q180,238 360,288 Q540,340 720,258 Q900,178 1100,258 Q1300,330 1440,278 L1440,400 L0,400Z"
              fill="url(#dg2)" opacity=".88"/>
        <path id="crest" d="M0,320 Q180,240 360,290 Q540,342 720,260 Q900,180 1100,260 Q1300,332 1440,280"
              fill="none" stroke="#C9A96E" stroke-width=".7" opacity=".3"/>
    </svg>
</div>

<!-- Particles -->
<div class="embers" id="embers"></div>
<div class="dust"   id="dust"></div>

<!-- Mode Switcher -->
<nav class="switcher">
    <button class="sw-btn on" data-m="night" onclick="setMode('night')"><span>🌙</span> Nuit</button>
    <button class="sw-btn"    data-m="dawn"  onclick="setMode('dawn')"><span>🌅</span> Aube</button>
    <button class="sw-btn"    data-m="noon"  onclick="setMode('noon')"><span>🌞</span> Midi</button>
</nav>

<main>
    <section class="hero">
        <!-- Sigil -->
        <div class="sigil">
            <svg id="sigil" viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg">
                <circle cx="45" cy="45" r="42" fill="none" stroke="#C9A96E" stroke-width=".8" opacity=".5" class="sr"/>
                <circle cx="45" cy="45" r="34" fill="none" stroke="#C9A96E" stroke-width=".4" opacity=".3" class="sr"/>
                <polygon id="sstar"
                    points="45,5 47,38 55,10 50,42 65,18 56,46 78,30 60,51 85,45 62,55 80,68 57,59 68,82 48,63 45,88 42,63 22,82 33,59 10,68 28,55 5,45 30,51 12,30 34,46 25,18 39,42 35,10 43,38"
                    fill="none" stroke="#C9A96E" stroke-width=".6" opacity=".6"/>
                <circle cx="45" cy="45" r="5" id="sdot" fill="#D4622A" opacity=".9"/>
                <circle cx="45" cy="45" r="2" fill="#F5E6C8"/>
            </svg>
        </div>

        <p class="eyebrow">Communauté · Renait-Sens</p>

        <h1>
            L'Ombre du Tassili
            <em>Guide des Nomades de l'Éveil</em>
        </h1>

        <div class="divider">
            <span class="div-line"></span>
            <span class="div-icon" id="dico">🌙</span>
            <span class="div-line"></span>
        </div>

        <p class="tagline">
            <span class="tl tl-night">
                Sous cette tente, chaque âme trouve son souffle.<br>
                Le sable efface hier, les étoiles éclairent demain.<br>
                Bienvenue, Nomade. 🌙
            </span>
            <span class="tl tl-dawn">
                Le ciel se teint de rose avant que le soleil ne parle.<br>
                C'est l'heure du premier souffle, du nouveau départ.<br>
                Lève-toi, Nomade — l'aube t'appartient. 💫
            </span>
            <span class="tl tl-noon">
                Sous le soleil de feu, le sable révèle sa vérité nue.<br>
                Pas d'ombre où se cacher — seulement toi, debout.<br>
                Marche, Nomade. Le désert te voit. 🐪
            </span>
        </p>

        <div class="cta-group">
            <a href="{{ route('register') }}" class="btn btn-p">⭐️ &nbsp;Rejoindre la Caravane</a>
            <a href="{{ route('login') }}"    class="btn btn-s">🌵 &nbsp;Retrouver ma Tente</a>
        </div>
    </section>

    <section class="modules-section">
        <p class="mod-label">Les 10 Modules du Chemin</p>
        <div class="mod-grid">
            @foreach([
                ['01','Reset'],['02','Reboot'],['03','Clarté'],['04','Ancrage'],['05','Silence'],
                ['06','Vision'],['07','Lâcher-prise'],['08','Connexion'],['09','Puissance'],['10','Renaissance'],
            ] as $m)
            <div class="mod-card">
                <span class="mod-num">{{ $m[0] }}</span>
                <span class="mod-name">{{ $m[1] }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <div class="pacte">
        <p class="pacte-title">Le Pacte de l'Aman</p>
        <p class="pacte-txt">
            « Sous cette tente, on dépose les armes.<br>
            La confidentialité est notre loi.<br>
            Le non-jugement, notre air.<br>
            La bienveillance radicale, notre feu. »
        </p>
        <span class="pacte-emoji" id="pemoji">⏳</span>
    </div>
</main>

<div class="scroll-hint">
    <span>Explorer</span>
    <div class="scroll-arrow"></div>
</div>

<script>
// ── CONFIG MODES ──
const CFG = {
    night: {
        d1a:'#1A1208', d1b:'#0A0A12', d2a:'#241A0A', d2b:'#0D0D1A',
        crest:'#C9A96E', icon:'🌙', emoji:'⏳',
        sr:'#C9A96E', dot:'#D4622A',
    },
    dawn: {
        d1a:'#5C3018', d1b:'#2A1208', d2a:'#7A4520', d2b:'#3D2010',
        crest:'#FFB464', icon:'💫', emoji:'🌅',
        sr:'#FFB464', dot:'#E05A20',
    },
    noon: {
        d1a:'#B8942A', d1b:'#7A5F18', d2a:'#C8A050', d2b:'#8A6820',
        crest:'#FFE066', icon:'🌞', emoji:'🐪',
        sr:'#C8880A', dot:'#C04A10',
    },
};

let mode = 'night';

function setMode(m) {
    mode = m;
    document.documentElement.setAttribute('data-mode', m);
    document.querySelectorAll('.sw-btn').forEach(b => b.classList.toggle('on', b.dataset.m === m));
    const c = CFG[m];
    // Dunes
    document.getElementById('d1a').setAttribute('stop-color', c.d1a);
    document.getElementById('d1b').setAttribute('stop-color', c.d1b);
    document.getElementById('d2a').setAttribute('stop-color', c.d2a);
    document.getElementById('d2b').setAttribute('stop-color', c.d2b);
    document.getElementById('crest').setAttribute('stroke', c.crest);
    // Icons
    document.getElementById('dico').textContent  = c.icon;
    document.getElementById('pemoji').textContent = c.emoji;
    // Sigil
    document.querySelectorAll('.sr').forEach(el => el.setAttribute('stroke', c.sr));
    document.getElementById('sstar').setAttribute('stroke', c.sr);
    document.getElementById('sdot').setAttribute('fill', c.dot);
}

// ── CANVAS PRINCIPAL ──
const cv = document.getElementById('sky-canvas');
const cx = cv.getContext('2d');
let W, H, stars = [], raf;

function resize() {
    W = cv.width = window.innerWidth;
    H = cv.height = window.innerHeight;
    stars = Array.from({length:260}, () => ({
        x: Math.random()*W, y: Math.random()*H*.72,
        r: Math.random()*1.2+.2, a: Math.random()*.8+.1,
        sp: Math.random()*.003+.001, ph: Math.random()*Math.PI*2,
    }));
}

function draw(t) {
    cx.clearRect(0,0,W,H);

    if (mode === 'night') {
        stars.forEach(s => {
            const a = s.a * (.55 + .45*Math.sin(t*s.sp*1000+s.ph));
            cx.beginPath(); cx.arc(s.x,s.y,s.r,0,Math.PI*2);
            cx.fillStyle = `rgba(245,230,200,${a})`; cx.fill();
        });
        // Moon
        const lx=W*.82, ly=H*.12, lr=29;
        const mg = cx.createRadialGradient(lx,ly,0,lx,ly,lr);
        mg.addColorStop(0,'rgba(245,230,200,.2)'); mg.addColorStop(1,'transparent');
        cx.beginPath(); cx.arc(lx,ly,lr,0,Math.PI*2); cx.fillStyle=mg; cx.fill();
        cx.beginPath(); cx.arc(lx,ly,13,-Math.PI*.65,Math.PI*.65);
        cx.fillStyle='rgba(230,210,165,.14)'; cx.fill();
    }

    if (mode === 'dawn') {
        // Residual stars
        stars.slice(0,55).forEach(s => {
            cx.beginPath(); cx.arc(s.x, s.y*.5, s.r*.65, 0, Math.PI*2);
            cx.fillStyle='rgba(255,240,220,.22)'; cx.fill();
        });
        // Rising sun
        const sx=W*.36, sy=H*.63, p=1+.012*Math.sin(t*.001);
        [['rgba(255,90,20,.07)',180],['rgba(255,160,60,.11)',110],['rgba(255,210,110,.17)',65]].forEach(([c,r])=>{
            const g=cx.createRadialGradient(sx,sy,0,sx,sy,r*p);
            g.addColorStop(0,c); g.addColorStop(1,'transparent');
            cx.beginPath(); cx.arc(sx,sy,r*p,0,Math.PI*2); cx.fillStyle=g; cx.fill();
        });
        const sg=cx.createRadialGradient(sx,sy,0,sx,sy,26*p);
        sg.addColorStop(0,'rgba(255,240,200,.9)'); sg.addColorStop(.5,'rgba(255,150,50,.65)'); sg.addColorStop(1,'rgba(255,80,20,0)');
        cx.beginPath(); cx.arc(sx,sy,26*p,0,Math.PI*2); cx.fillStyle=sg; cx.fill();
        // Rays
        for(let i=0;i<8;i++){
            const a=(i/8)*Math.PI*2+t*.0002, len=55+18*Math.sin(t*.0008+i);
            cx.beginPath(); cx.moveTo(sx+Math.cos(a)*28,sy+Math.sin(a)*28);
            cx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
            cx.strokeStyle=`rgba(255,190,90,${.07+.04*Math.sin(t*.001+i)})`; cx.lineWidth=1.5; cx.stroke();
        }
    }

    if (mode === 'noon') {
        const sx=W*.5, sy=H*.07, p=1+.007*Math.sin(t*.0015);
        [{r:170,c:'rgba(255,248,180,.05)'},{r:105,c:'rgba(255,230,120,.1)'},{r:65,c:'rgba(255,220,100,.17)'},{r:34,c:'rgba(255,252,220,.5)'}].forEach(({r,c})=>{
            const g=cx.createRadialGradient(sx,sy,0,sx,sy,r*p);
            g.addColorStop(0,c); g.addColorStop(1,'transparent');
            cx.beginPath(); cx.arc(sx,sy,r*p,0,Math.PI*2); cx.fillStyle=g; cx.fill();
        });
        for(let i=0;i<12;i++){
            const a=(i/12)*Math.PI*2+t*.00005, len=120+28*Math.sin(t*.0006+i*.8);
            cx.beginPath(); cx.moveTo(sx+Math.cos(a)*36,sy+Math.sin(a)*36);
            cx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
            cx.strokeStyle=`rgba(255,228,140,${.07+.04*Math.sin(t*.0012+i)})`; cx.lineWidth=2; cx.stroke();
        }
    }

    raf = requestAnimationFrame(draw);
}

// ── BIRDS CANVAS (aube) ──
const bc=document.getElementById('bird-canvas'), bx=bc.getContext('2d');
let bW,bH;
const birds=Array.from({length:12},(_,i)=>({
    x:-60-i*90, y:0, spd:.55+Math.random()*.55,
    flap:Math.random()*Math.PI*2, sz:7+Math.random()*5,
    yoff: Math.random()*140,
}));

function resizeB(){
    bW=bc.width=window.innerWidth; bH=bc.height=window.innerHeight;
    birds.forEach(b=>b.y=bH*.22+b.yoff);
}

function drawBirds(t){
    bx.clearRect(0,0,bW,bH);
    if(mode==='dawn'){
        birds.forEach(b=>{
            b.x+=b.spd; b.flap+=.055;
            if(b.x>bW+80) b.x=-80;
            const wy=Math.sin(b.flap)*b.sz*.9;
            bx.beginPath();
            bx.moveTo(b.x-b.sz,b.y+wy);
            bx.quadraticCurveTo(b.x,b.y-wy*1.3,b.x+b.sz,b.y+wy);
            bx.strokeStyle='rgba(20,6,2,.72)'; bx.lineWidth=2.2; bx.stroke();
        });
    }
    requestAnimationFrame(drawBirds);
}

// ── EMBERS ──
(()=>{
    const c=document.getElementById('embers');
    for(let i=0;i<16;i++){
        const e=document.createElement('div'); e.className='ember';
        e.style.setProperty('--d',(3+Math.random()*4)+'s');
        e.style.setProperty('--dl',(Math.random()*6)+'s');
        e.style.setProperty('--dr',((Math.random()-.5)*95)+'px');
        e.style.left=(Math.random()*100-50)+'px';
        e.style.background=Math.random()>.5?'#F07A3A':'#FFC878';
        const sz=Math.random()*3+1; e.style.width=e.style.height=sz+'px';
        c.appendChild(e);
    }
})();

// ── DUST ──
(()=>{
    const c=document.getElementById('dust');
    for(let i=0;i<18;i++){
        const e=document.createElement('div'); e.className='dp';
        e.style.setProperty('--d',(6+Math.random()*10)+'s');
        e.style.setProperty('--dl',(Math.random()*14)+'s');
        e.style.setProperty('--dy',((Math.random()-.5)*55)+'px');
        e.style.top=(20+Math.random()*60)+'%';
        const sz=Math.random()*9+3;
        e.style.width=sz+'px'; e.style.height=(sz*.38)+'px';
        c.appendChild(e);
    }
})();

// ── PARALLAX ──
window.addEventListener('mousemove',e=>{
    const x=(e.clientX/window.innerWidth-.5)*14;
    const y=(e.clientY/window.innerHeight-.5)*6;
    document.getElementById('dunes').style.transform=`translateX(${x*.3}px) translateY(${y*.2}px)`;
});

// ── MODULES STAGGER ──
document.querySelectorAll('.mod-card').forEach((c,i)=>{
    c.style.opacity='0'; c.style.transform='translateY(16px)';
    c.style.transition='opacity .6s, transform .6s, background 1.4s';
    setTimeout(()=>{ c.style.opacity='1'; c.style.transform='none'; }, 1800+i*90);
});

// ── INIT ──
window.addEventListener('resize',()=>{ resize(); resizeB(); });
resize(); resizeB();
requestAnimationFrame(draw);
requestAnimationFrame(drawBirds);

// Auto-detect heure réelle
(()=>{
    const h=new Date().getHours();
    if(h>=5&&h<9)       setMode('dawn');
    else if(h>=9&&h<19) setMode('noon');
    else                 setMode('night');
})();
</script>
</body>
</html>