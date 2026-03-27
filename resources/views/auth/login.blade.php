<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div id="lp">
        <div class="lp-bg"></div>
        <div class="lp-grain"></div>

        <a href="{{ url('/') }}" class="lp-back">← Retour à l'accueil</a>

        {{-- TRIPTYQUE : 3 toiles --}}
        <div class="triptych">
            <div class="canvas-wrap canvas-left">
                <div class="canvas-inner">
                    <div class="canvas-img" style="background-image:url('/images/carnet_intime.png'); background-position: left center;"></div>
                    <div class="canvas-edge canvas-edge-right"></div>
                </div>
                <div class="canvas-shadow"></div>
            </div>

            <div class="canvas-wrap canvas-center">
                <div class="canvas-inner">
                    <div class="canvas-img" style="background-image:url('/images/carnet_intime.png'); background-position: center center;"></div>
                    <div class="canvas-edge canvas-edge-left"></div>
                    <div class="canvas-edge canvas-edge-right"></div>
                </div>
                <div class="canvas-shadow"></div>
            </div>

            <div class="canvas-wrap canvas-right">
                <div class="canvas-inner">
                    <div class="canvas-img" style="background-image:url('/images/carnet_intime.png'); background-position: right center;"></div>
                    <div class="canvas-edge canvas-edge-left"></div>
                </div>
                <div class="canvas-shadow"></div>
            </div>
        </div>

        {{-- FORMULAIRE centré par-dessus --}}
        <div class="lp-center">
            <p class="lp-eyebrow">✦ Espace Membre ✦</p>
            <h1 class="lp-title">Renaît-Sens</h1>
            <p class="lp-sub">Retrouve ton chemin, Nomade.</p>

            <div class="lp-card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="lp-field">
                        <label for="email" class="lp-label">Adresse e-mail</label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}" required autofocus
                               autocomplete="username" placeholder="ton@email.com"
                               class="lp-input" />
                        <x-input-error :messages="$errors->get('email')" class="lp-err" />
                    </div>

                    <div class="lp-field">
                        <label for="password" class="lp-label">Mot de passe</label>
                        <div class="lp-pw">
                            <input id="password" name="password" type="password"
                                   required autocomplete="current-password"
                                   placeholder="••••••••••" class="lp-input lp-input-pw" />
                            <button type="button" onclick="togglePw()" class="lp-eye">
                                <svg id="eo" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12C3.5 6.5 12 2.5 12 2.5S20.5 6.5 22.5 12C20.5 17.5 12 21.5 12 21.5S3.5 17.5 1.5 12z"/>
                                    <circle cx="12" cy="12" r="3.5"/>
                                </svg>
                                <svg id="ec" style="display:none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.68A3.5 3.5 0 0113.32 13.5M6.7 6.7C4.2 8.2 2.5 10.2 1.5 12c2 5.5 10.5 9.5 10.5 9.5a20 20 0 005.3-2.7"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="lp-err" />
                    </div>

                    <div class="lp-meta">
                        <label class="lp-remember">
                            <input name="remember" type="checkbox" class="lp-check" />
                            Se souvenir de moi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="lp-forgot">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    <button type="submit" class="lp-btn">Ouvre ton carnet ✦</button>
                </form>
            </div>

            @if (Route::has('register'))
                <p class="lp-register">
                    Pas encore membre ?
                    <a href="{{ route('traverser') }}">Rejoindre la caravane →</a>
                </p>
            @endif
            <p class="lp-quote">« Le désert ne ment jamais. »</p>
        </div>

    </div>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        #lp {
            position: fixed; inset: 0; z-index: 9999;
            font-family: 'Cormorant Garamond', Georgia, serif;
            overflow-y: auto;
            background: #1A0E06;
        }

        .lp-grain {
            position: fixed; inset: 0; z-index: 3; pointer-events: none; opacity: .12;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 200px;
        }

        .lp-bg {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse at 50% 50%, #2A1508 0%, #100805 100%);
        }

        /* ══ BOUTON RETOUR ══ */
        .lp-back {
            position: fixed; top: 1.2rem; left: 1.4rem; z-index: 10000;
            padding: .5rem 1.1rem;
            background: rgba(8,4,1,.82); backdrop-filter: blur(12px);
            border: 1px solid rgba(201,169,110,.35);
            color: #F5E6C8; text-decoration: none;
            font-family: 'Philosopher', serif;
            font-size: .72rem; letter-spacing: .16em; text-transform: uppercase;
            transition: all .3s;
        }
        .lp-back:hover { background: rgba(212,98,42,.88); color: #fff; }

        /* ══ TRIPTYQUE ══ */
        .triptych {
            position: fixed; inset: 0; z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 3rem 3rem;
        }

        .canvas-wrap {
            position: relative;
            flex: 1;
            max-width: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Panneau central plus haut */
        .canvas-center { max-width: 380px; }
        .canvas-center .canvas-inner { height: 82vh; }
        .canvas-left .canvas-inner,
        .canvas-right .canvas-inner { height: 68vh; }

        .canvas-inner {
            width: 100%;
            position: relative;
            overflow: hidden;
            /* Effet toile tendue : bords arrondis très légèrement */
            border-radius: 3px;
            /* Cadre toile */
            box-shadow:
                /* Bord avant de la toile */
                0 0 0 2px rgba(120, 80, 30, .55),
                /* Relief bois */
                2px 2px 0 2px rgba(80, 45, 10, .70),
                /* Ombre portée profonde */
                8px 14px 40px rgba(0,0,0,.75),
                16px 24px 80px rgba(0,0,0,.55);
        }

        .canvas-img {
            width: 100%; height: 100%;
            background-size: 300% 100%; /* l'image est étirée sur les 3 toiles */
            background-repeat: no-repeat;
            filter: brightness(.72) saturate(1.15);
            transition: filter .5s;
        }
        .canvas-left   .canvas-img { background-size: 300% 100%; }
        .canvas-center .canvas-img { background-size: 300% 100%; }
        .canvas-right  .canvas-img { background-size: 300% 100%; }

        .canvas-wrap:hover .canvas-img { filter: brightness(.85) saturate(1.25); }

        /* Tranches latérales de la toile (effet 3D) */
        .canvas-edge {
            position: absolute; top: 0; bottom: 0; width: 14px;
            background: linear-gradient(90deg, rgba(60,30,5,.90), rgba(40,18,3,.95));
        }
        .canvas-edge-right { right: 0; background: linear-gradient(90deg, rgba(40,18,3,.60), rgba(25,10,2,.92)); }
        .canvas-edge-left  { left: 0;  background: linear-gradient(90deg, rgba(25,10,2,.92), rgba(40,18,3,.60)); }

        /* Reflet haut de la toile */
        .canvas-inner::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 35%;
            background: linear-gradient(180deg, rgba(255,200,120,.08) 0%, transparent 100%);
            z-index: 2; pointer-events: none;
        }

        /* Ombre portée sous la toile */
        .canvas-shadow {
            width: 80%; height: 18px; margin-top: -4px;
            background: radial-gradient(ellipse, rgba(0,0,0,.55) 0%, transparent 70%);
            filter: blur(6px);
        }

        /* ══ FORMULAIRE centré par-dessus ══ */
        .lp-center {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 5rem 1.5rem 3rem;
            text-align: center;
            animation: fadeUp 1.2s cubic-bezier(.16,1,.3,1) both;
        }

        .lp-eyebrow {
            font-family: 'Philosopher', serif;
            font-size: .82rem; letter-spacing: .42em; text-transform: uppercase;
            color: #FFD580; margin-bottom: .9rem;
            text-shadow: 0 1px 8px rgba(0,0,0,.9);
        }

        .lp-title {
            font-family: 'Cinzel Decorative', 'Cinzel', serif;
            font-size: clamp(2.8rem, 5vw, 4.5rem);
            font-weight: 700; line-height: 1.05; color: #fff;
            text-shadow: 0 0 60px rgba(212,98,42,.65), 0 3px 24px rgba(0,0,0,.99);
            margin-bottom: .65rem;
        }

        .lp-sub {
            font-size: 1.25rem; font-style: italic;
            color: #FFE8B0; margin-bottom: 2rem;
            text-shadow: 0 1px 12px rgba(0,0,0,.95);
        }

        /* ── Card ── */
        .lp-card {
            width: 100%; max-width: 460px;
            background: rgba(5, 2, 1, 0.86);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(201,169,110,.28);
            padding: 2.2rem 2.2rem 2.4rem;
            margin-bottom: 1.4rem;
            box-shadow: 0 30px 90px rgba(0,0,0,.75), 0 0 0 1px rgba(255,255,255,.03) inset;
            position: relative;
            text-align: left;
        }
        .lp-card::before, .lp-card::after {
            content: ''; position: absolute;
            width: 22px; height: 22px;
            border-style: solid; border-color: rgba(201,169,110,.45);
        }
        .lp-card::before { top:-1px; left:-1px; border-width: 2px 0 0 2px; }
        .lp-card::after  { bottom:-1px; right:-1px; border-width: 0 2px 2px 0; }

        .lp-field { margin-bottom: 1.3rem; }
        .lp-label {
            display: block;
            font-family: 'Philosopher', serif;
            font-size: .74rem; letter-spacing: .26em; text-transform: uppercase;
            color: #FFD580; margin-bottom: .5rem;
        }
        .lp-input {
            width: 100%; padding: .88rem 1rem;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(201,169,110,.28);
            color: #fff;
            font-family: 'Cormorant Garamond', serif; font-size: 1.08rem;
            outline: none; transition: border-color .3s, background .3s;
        }
        .lp-input::placeholder { color: rgba(201,169,110,.42); font-style: italic; }
        .lp-input:focus { border-color: rgba(201,169,110,.68); background: rgba(255,255,255,.10); }
        .lp-input-pw { padding-right: 2.8rem; }
        .lp-err { margin-top: .35rem; font-size: .8rem; color: #F07A3A; font-style: italic; }

        .lp-pw { position: relative; }
        .lp-eye {
            position: absolute; right: .7rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: rgba(201,169,110,.50); transition: color .3s;
        }
        .lp-eye:hover { color: rgba(201,169,110,.9); }

        .lp-meta {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.6rem;
        }
        .lp-remember {
            display: flex; align-items: center; gap: .45rem;
            font-size: .92rem; color: #FFE0A0; font-style: italic; cursor: pointer;
        }
        .lp-check { accent-color: #D4622A; width: 14px; height: 14px; }
        .lp-forgot {
            font-size: .92rem; font-style: italic;
            color: #FFD580; text-decoration: none; transition: color .3s;
        }
        .lp-forgot:hover { color: #fff; }

        .lp-btn {
            width: 100%; padding: 1.05rem;
            background: linear-gradient(135deg, #D4622A, #A03810);
            color: #fff; border: none;
            font-family: 'Philosopher', serif;
            font-size: .92rem; letter-spacing: .2em; text-transform: uppercase;
            cursor: pointer; box-shadow: 0 4px 24px rgba(212,98,42,.55);
            transition: all .35s; position: relative; overflow: hidden;
        }
        .lp-btn::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.13), transparent);
            transform: translateX(-100%); transition: transform .5s;
        }
        .lp-btn:hover::before { transform: translateX(100%); }
        .lp-btn:hover { filter: brightness(1.12); transform: translateY(-1px); }

        .lp-register {
            font-size: 1rem; color: #FFE0A0; font-style: italic;
            margin-bottom: .7rem;
            text-shadow: 0 1px 8px rgba(0,0,0,.9);
        }
        .lp-register a {
            color: #F07A3A; font-weight: 600;
            text-decoration: none; margin-left: .3rem; transition: color .3s;
        }
        .lp-register a:hover { color: #fff; }

        .lp-quote {
            font-size: .9rem; font-style: italic;
            color: rgba(255,215,120,.55); letter-spacing: .05em;
            text-shadow: 0 1px 6px rgba(0,0,0,.8);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: none; }
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .triptych { gap: 8px; padding: 2rem 1rem; }
            .canvas-center .canvas-inner { height: 60vh; }
            .canvas-left .canvas-inner,
            .canvas-right .canvas-inner { height: 50vh; }
        }
    </style>

    <script>
        function togglePw() {
            const i = document.getElementById('password');
            const h = i.type === 'password';
            i.type = h ? 'text' : 'password';
            document.getElementById('eo').style.display = h ? 'none' : '';
            document.getElementById('ec').style.display = h ? '' : 'none';
        }
    </script>
</x-guest-layout>