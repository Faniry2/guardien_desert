<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renait-Sens — L'Ombre du Tassili</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital@0;1&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sand: #C9A96E;
            --sand-light: #E8C98A;
            --sand-pale: #F5E6C8;
            --night: #0A0A12;
            --deep: #0D0D1A;
            --indigo: #141428;
            --ember: #D4622A;
            --ember-glow: #F07A3A;
            --star: #FFF8E7;
            --sage: #6B8F6B;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--night);
            color: var(--sand-pale);
            font-family: 'Cormorant Garamond', Georgia, serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ─── CANVAS ÉTOILES ─── */
        #starfield {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        /* ─── GRAINS DE SABLE ─── */
        .grain-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: 0.35;
        }

        /* ─── DUNES HORIZON ─── */
        .dune-horizon {
            position: fixed;
            bottom: 0; left: 0;
            width: 100%; height: 45vh;
            z-index: 2;
            pointer-events: none;
        }

        .dune-horizon svg { width: 100%; height: 100%; }

        /* ─── LAYOUT ─── */
        main {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* ─── HERO ─── */
        .hero {
            text-align: center;
            max-width: 860px;
            padding: 4rem 2rem 2rem;
            animation: fadeUp 1.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .sigil {
            display: inline-block;
            width: 90px; height: 90px;
            margin-bottom: 2.5rem;
            animation: sigil-spin 30s linear infinite, fadeIn 2s ease both;
            opacity: 0.9;
        }

        .eyebrow {
            font-family: 'Philosopher', serif;
            font-size: 0.78rem;
            letter-spacing: 0.45em;
            text-transform: uppercase;
            color: var(--sand);
            opacity: 0.7;
            margin-bottom: 1.5rem;
            animation: fadeUp 2s 0.3s both;
        }

        h1 {
            font-family: 'Cinzel Decorative', serif;
            font-size: clamp(2.2rem, 6vw, 4.5rem);
            font-weight: 700;
            line-height: 1.1;
            color: var(--sand-light);
            text-shadow: 0 0 60px rgba(201,169,110,0.4), 0 2px 4px rgba(0,0,0,0.8);
            margin-bottom: 0.4rem;
            animation: fadeUp 2s 0.5s both;
        }

        h1 em {
            display: block;
            font-style: italic;
            font-size: 0.55em;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300;
            color: var(--sand);
            letter-spacing: 0.2em;
            margin-top: 0.4rem;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin: 2.5rem auto;
            width: fit-content;
            animation: fadeIn 2s 0.9s both;
        }

        .divider-line {
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--sand), transparent);
        }

        .divider-glyph {
            font-size: 1.3rem;
            color: var(--sand);
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .tagline {
            font-size: clamp(1.1rem, 2.5vw, 1.5rem);
            font-weight: 300;
            font-style: italic;
            color: var(--sand-pale);
            opacity: 0.85;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 3rem;
            animation: fadeUp 2s 1s both;
        }

        /* ─── CTA ─── */
        .cta-group {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp 2s 1.3s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 2.2rem;
            border-radius: 2px;
            font-family: 'Philosopher', serif;
            font-size: 0.9rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn:hover::before { opacity: 1; }

        .btn-primary {
            background: linear-gradient(135deg, var(--ember), #A84A1A);
            color: var(--sand-pale);
            box-shadow: 0 4px 30px rgba(212,98,42,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(212,98,42,0.5), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .btn-secondary {
            background: transparent;
            color: var(--sand);
            border: 1px solid rgba(201,169,110,0.4);
        }

        .btn-secondary:hover {
            background: rgba(201,169,110,0.08);
            border-color: var(--sand);
            transform: translateY(-2px);
        }

        /* ─── MODULES PILLARS ─── */
        .modules-section {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1100px;
            margin: 5rem auto 2rem;
            padding: 0 2rem;
            animation: fadeUp 2s 1.6s both;
        }

        .modules-label {
            text-align: center;
            font-family: 'Philosopher', serif;
            font-size: 0.75rem;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: var(--sand);
            opacity: 0.55;
            margin-bottom: 2rem;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1px;
            background: rgba(201,169,110,0.1);
            border: 1px solid rgba(201,169,110,0.12);
        }

        .module-card {
            background: rgba(10,10,18,0.7);
            padding: 1.5rem 1rem;
            text-align: center;
            backdrop-filter: blur(8px);
            transition: background 0.4s, transform 0.3s;
            cursor: default;
            position: relative;
        }

        .module-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 0; height: 2px;
            background: var(--ember-glow);
            transition: width 0.4s;
        }

        .module-card:hover {
            background: rgba(201,169,110,0.06);
        }

        .module-card:hover::after { width: 60%; }

        .module-num {
            font-family: 'Cinzel Decorative', serif;
            font-size: 0.65rem;
            color: var(--ember);
            letter-spacing: 0.2em;
            display: block;
            margin-bottom: 0.5rem;
        }

        .module-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-style: italic;
            color: var(--sand-light);
            font-weight: 300;
        }

        /* ─── PACTE BANNER ─── */
        .pacte-banner {
            position: relative;
            z-index: 10;
            max-width: 680px;
            margin: 4rem auto 6rem;
            padding: 2.5rem 3rem;
            border: 1px solid rgba(201,169,110,0.2);
            background: rgba(13,13,26,0.6);
            backdrop-filter: blur(12px);
            text-align: center;
            animation: fadeIn 2.5s 2s both;
        }

        .pacte-banner::before,
        .pacte-banner::after {
            content: '';
            position: absolute;
            width: 40px; height: 40px;
            border-color: var(--sand);
            border-style: solid;
            opacity: 0.4;
        }

        .pacte-banner::before { top: -1px; left: -1px; border-width: 2px 0 0 2px; }
        .pacte-banner::after { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; }

        .pacte-title {
            font-family: 'Cinzel Decorative', serif;
            font-size: 0.8rem;
            letter-spacing: 0.3em;
            color: var(--sand);
            text-transform: uppercase;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .pacte-text {
            font-size: 1.15rem;
            font-style: italic;
            font-weight: 300;
            color: var(--sand-pale);
            line-height: 1.8;
            opacity: 0.9;
        }

        .pacte-emoji {
            font-size: 1.5rem;
            display: block;
            margin-top: 1rem;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* ─── FIRE EMBER PARTICLES ─── */
        .embers-container {
            position: fixed;
            bottom: 30vh;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
            z-index: 5;
        }

        .ember-particle {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: var(--ember-glow);
            animation: ember-rise var(--dur, 4s) var(--delay, 0s) ease-out infinite;
            opacity: 0;
        }

        /* ─── SCROLL INDICATOR ─── */
        .scroll-hint {
            position: fixed;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.4;
            animation: fadeIn 3s 2.5s both, float 3s ease-in-out infinite;
            font-family: 'Philosopher', serif;
            font-size: 0.7rem;
            letter-spacing: 0.3em;
            color: var(--sand);
            text-transform: uppercase;
        }

        .scroll-arrow {
            width: 20px; height: 20px;
            border-right: 1px solid var(--sand);
            border-bottom: 1px solid var(--sand);
            transform: rotate(45deg);
        }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes sigil-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.8; text-shadow: 0 0 8px rgba(201,169,110,0.3); }
            50% { opacity: 1; text-shadow: 0 0 20px rgba(201,169,110,0.7); }
        }

        @keyframes ember-rise {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0.8; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-120px) translateX(var(--drift, 20px)) scale(0); opacity: 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-6px); }
        }

        @keyframes dune-shimmer {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.04); }
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 600px) {
            .modules-grid { grid-template-columns: repeat(2, 1fr); }
            .pacte-banner { padding: 2rem 1.5rem; }
            .cta-group { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

<!-- Starfield canvas -->
<canvas id="starfield"></canvas>

<!-- Grain texture -->
<div class="grain-overlay"></div>

<!-- Dunes SVG -->
<div class="dune-horizon">
    <svg viewBox="0 0 1440 400" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="duneGrad1" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#1A1208;stop-opacity:0.9"/>
                <stop offset="100%" style="stop-color:#0A0A12;stop-opacity:1"/>
            </linearGradient>
            <linearGradient id="duneGrad2" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#241A0A;stop-opacity:0.7"/>
                <stop offset="100%" style="stop-color:#0D0D1A;stop-opacity:1"/>
            </linearGradient>
            <filter id="duneBlur">
                <feGaussianBlur stdDeviation="2"/>
            </filter>
        </defs>
        <!-- Dune arrière -->
        <path d="M0,280 Q200,180 400,240 Q600,300 800,210 Q1000,120 1200,200 Q1380,270 1440,230 L1440,400 L0,400 Z"
              fill="url(#duneGrad1)" opacity="0.5" filter="url(#duneBlur)"/>
        <!-- Dune principale -->
        <path d="M0,320 Q180,240 360,290 Q540,340 720,260 Q900,180 1100,260 Q1300,330 1440,280 L1440,400 L0,400 Z"
              fill="url(#duneGrad2)" opacity="0.8"/>
        <!-- Crête lumineuse -->
        <path d="M0,322 Q180,242 360,292 Q540,342 720,262 Q900,182 1100,262 Q1300,332 1440,282"
              fill="none" stroke="#C9A96E" stroke-width="0.6" opacity="0.25"/>
    </svg>
</div>

<!-- Ember particles -->
<div class="embers-container" id="embers"></div>

<!-- MAIN CONTENT -->
<main>
    <section class="hero">

        <!-- Sigil géométrique -->
        <div class="sigil">
            <svg viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg">
                <circle cx="45" cy="45" r="42" fill="none" stroke="#C9A96E" stroke-width="0.8" opacity="0.5"/>
                <circle cx="45" cy="45" r="34" fill="none" stroke="#C9A96E" stroke-width="0.4" opacity="0.3"/>
                <!-- Étoile 8 branches -->
                <polygon points="45,5 47,38 55,10 50,42 65,18 56,46 78,30 60,51 85,45 62,55 80,68 57,59 68,82 48,63 45,88 42,63 22,82 33,59 10,68 28,55 5,45 30,51 12,30 34,46 25,18 39,42 35,10 43,38"
                         fill="none" stroke="#C9A96E" stroke-width="0.6" opacity="0.6"/>
                <circle cx="45" cy="45" r="5" fill="#D4622A" opacity="0.9"/>
                <circle cx="45" cy="45" r="2" fill="#F5E6C8"/>
            </svg>
        </div>

        <p class="eyebrow">Communauté · Renait-Sens</p>

        <h1>
            L'Ombre du Tassili
            <em>Guide des Nomades de l'Éveil</em>
        </h1>

        <div class="divider">
            <span class="divider-line"></span>
            <span class="divider-glyph">🌙</span>
            <span class="divider-line"></span>
        </div>

        <p class="tagline">
            Sous cette tente, chaque âme trouve son souffle. Le sable efface hier,
            les étoiles éclairent demain. Bienvenue, Nomade.
        </p>

        {{-- <div class="cta-group">
            <a href="{{ route('register') }}" class="btn btn-primary">
                ⭐️ &nbsp;Rejoindre la Caravane
            </a>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                🌵 &nbsp;Retrouver ma Tente
            </a>
        </div> --}}
    </section>

    <!-- Les 10 Modules -->
    <section class="modules-section">
        <p class="modules-label">Les 10 Modules du Chemin</p>
        <div class="modules-grid">
            @foreach([
                ['01', 'Reset'],
                ['02', 'Reboot'],
                ['03', 'Clarté'],
                ['04', 'Ancrage'],
                ['05', 'Silence'],
                ['06', 'Vision'],
                ['07', 'Lâcher-prise'],
                ['08', 'Connexion'],
                ['09', 'Puissance'],
                ['10', 'Renaissance'],
            ] as $module)
            <div class="module-card">
                <span class="module-num">{{ $module[0] }}</span>
                <span class="module-name">{{ $module[1] }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Pacte de l'Aman -->
    <div class="pacte-banner">
        <p class="pacte-title">Le Pacte de l'Aman</p>
        <p class="pacte-text">
            « Sous cette tente, on dépose les armes.<br>
            La confidentialité est notre loi.<br>
            Le non-jugement, notre air.<br>
            La bienveillance radicale, notre feu. »
        </p>
        <span class="pacte-emoji">⏳</span>
    </div>
</main>

<!-- Scroll hint -->
<div class="scroll-hint">
    <span>Explore</span>
    <div class="scroll-arrow"></div>
</div>

<script>
// ─── STARFIELD ───
(function () {
    const canvas = document.getElementById('starfield');
    const ctx = canvas.getContext('2d');
    let W, H, stars = [];

    function resize() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }

    function init() {
        stars = [];
        for (let i = 0; i < 280; i++) {
            stars.push({
                x: Math.random() * W,
                y: Math.random() * H * 0.75,
                r: Math.random() * 1.2 + 0.2,
                alpha: Math.random() * 0.7 + 0.1,
                speed: Math.random() * 0.004 + 0.001,
                phase: Math.random() * Math.PI * 2,
            });
        }
    }

    function draw(t) {
        ctx.clearRect(0, 0, W, H);
        stars.forEach(s => {
            const a = s.alpha * (0.6 + 0.4 * Math.sin(t * s.speed + s.phase));
            ctx.beginPath();
            ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(245,230,200,${a})`;
            ctx.fill();
        });

        // Lune croissant
        const lx = W * 0.82, ly = H * 0.12, lr = 28;
        const grd = ctx.createRadialGradient(lx, ly, 0, lx, ly, lr);
        grd.addColorStop(0, 'rgba(245,230,200,0.18)');
        grd.addColorStop(1, 'rgba(245,230,200,0)');
        ctx.beginPath();
        ctx.arc(lx, ly, lr, 0, Math.PI * 2);
        ctx.fillStyle = grd;
        ctx.fill();

        ctx.save();
        ctx.globalCompositeOperation = 'source-over';
        ctx.beginPath();
        ctx.arc(lx, ly, 14, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(230,210,170,0.08)';
        ctx.fill();
        // Croissant
        ctx.beginPath();
        ctx.arc(lx, ly, 12, -Math.PI * 0.6, Math.PI * 0.6);
        ctx.fillStyle = 'rgba(230,210,170,0.13)';
        ctx.fill();
        ctx.restore();

        requestAnimationFrame(draw);
    }

    window.addEventListener('resize', () => { resize(); init(); });
    resize(); init();
    requestAnimationFrame(draw);
})();

// ─── EMBER PARTICLES ───
(function () {
    const container = document.getElementById('embers');
    const count = 14;
    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.className = 'ember-particle';
        const angle = (Math.random() - 0.5) * 80;
        el.style.setProperty('--dur', (3 + Math.random() * 4) + 's');
        el.style.setProperty('--delay', (Math.random() * 5) + 's');
        el.style.setProperty('--drift', angle + 'px');
        el.style.left = (Math.random() * 80 - 40) + 'px';
        const hue = Math.random() > 0.5 ? '#F07A3A' : '#FFC87A';
        el.style.background = hue;
        el.style.width = el.style.height = (Math.random() * 3 + 1) + 'px';
        container.appendChild(el);
    }
})();

// ─── PARALLAX DUNES ───
window.addEventListener('mousemove', (e) => {
    const x = (e.clientX / window.innerWidth - 0.5) * 12;
    const y = (e.clientY / window.innerHeight - 0.5) * 6;
    document.querySelector('.dune-horizon').style.transform =
        `translateX(${x * 0.3}px) translateY(${y * 0.2}px)`;
});

// ─── MODULES STAGGER ───
document.querySelectorAll('.module-card').forEach((card, i) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(16px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease, background 0.4s';
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 1800 + i * 80);
});
</script>
</body>
</html>