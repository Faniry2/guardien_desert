<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Full-screen background --}}
    <div class="login-page">

        {{-- Background image layer --}}
        <div class="login-bg"></div>

        {{-- Dark overlay for readability --}}
        <div class="login-overlay"></div>

        {{-- Grain texture --}}
        <div class="login-grain"></div>

        {{-- Ember particles --}}
        <div class="embers" id="embers"></div>

        {{-- Bouton retour accueil --}}
        <a href="{{ url('/') }}" class="back-home">
            ← Retour à l'accueil
        </a>

        {{-- Content --}}
        <div class="login-content">

            {{-- Header --}}
            <div class="login-header">
                <p class="login-eyebrow">Espace Membre</p>
                <h1 class="login-title">Renaît-Sens</h1>
                <p class="login-subtitle">Retrouve ton chemin, Nomade.</p>
            </div>

            {{-- Card --}}
            <div class="login-card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="field-group">
                        <label for="email" class="field-label">Adresse e-mail</label>
                        <div class="field-wrap">
                            <span class="field-icon">✦</span>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email') }}"
                                   required autofocus autocomplete="username"
                                   placeholder="ton@email.com"
                                   class="field-input" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="field-error" />
                    </div>

                    {{-- Password --}}
                    <div class="field-group">
                        <label for="password" class="field-label">Mot de passe</label>
                        <div class="field-wrap">
                            <span class="field-icon">◈</span>
                            <input id="password" name="password" type="password"
                                   required autocomplete="current-password"
                                   placeholder="••••••••••••"
                                   class="field-input" style="padding-right:3rem" />
                            <button type="button" onclick="togglePassword()" class="eye-btn" title="Afficher / masquer">
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="eye-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12C3.5 6.5 12 2.5 12 2.5S20.5 6.5 22.5 12C20.5 17.5 12 21.5 12 21.5S3.5 17.5 1.5 12z" />
                                    <circle cx="12" cy="12" r="3.5" />
                                </svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="eye-svg hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.68A3.5 3.5 0 0113.32 13.5M6.7 6.7C4.2 8.2 2.5 10.2 1.5 12c2 5.5 10.5 9.5 10.5 9.5a20 20 0 005.3-2.7M9.9 4.2A11 11 0 0112 3.5c0 0 8.5 4 10.5 8.5-.7 1.6-1.8 3-3.1 4.2" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="field-error" />
                    </div>

                    {{-- Remember + forgot --}}
                    <div class="login-meta">
                        <label class="remember-label">
                            <input id="remember_me" name="remember" type="checkbox" class="remember-check" />
                            <span>Se souvenir de moi</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="login-btn">
                        Ouvre ton carnet ✦
                    </button>
                </form>
            </div>

            {{-- Register link --}}
            @if (Route::has('register'))
                <p class="login-register">
                    Pas encore membre ?
                    <a href="{{ route('traverser') }}">Rejoindre la caravane →</a>
                </p>
            @endif

            {{-- Quote --}}
            <p class="login-quote">« Le désert ne ment jamais. »</p>
        </div>
    </div>

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        .login-page {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', Georgia, serif;
            overflow: hidden;
        }

        /* ── Background image ── */
        .login-bg {
            position: fixed;
            inset: 0;
            background-image: url('/images/carnet_intime.png');
            background-size: ;
            background-position: center top;
            z-index: 0;
        }

        /* ── Dark overlay ── */
        .login-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(2, 1, 8, 0.62) 0%,
                rgba(5, 2, 12, 0.48) 40%,
                rgba(8, 3, 2, 0.70) 100%
            );
            z-index: 1;
        }

        /* ── Grain ── */
        .login-grain {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            opacity: 0.22;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 200px;
        }

        /* ── Embers ── */
        .embers {
            position: fixed;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 400px; height: 400px;
            pointer-events: none;
            z-index: 3;
        }
        .ember {
            position: absolute;
            border-radius: 50%;
            animation: ember-rise var(--d) var(--dl) ease-out infinite;
            opacity: 0;
        }
        @keyframes ember-rise {
            0%   { transform: translateY(0) translateX(0) scale(1); opacity: .9; }
            60%  { opacity: .4; }
            100% { transform: translateY(-220px) translateX(var(--dr, 20px)) scale(.08); opacity: 0; }
        }

        /* ── Content wrapper ── */
        .login-content {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: up 1.6s cubic-bezier(.16,1,.3,1) both;
        }

        /* ── Bouton retour ── */
        .back-home {
            position: fixed;
            top: 1.5rem;
            left: 1.8rem;
            z-index: 50;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.3rem;
            background: rgba(8, 5, 2, .65);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(201, 169, 110, .30);
            color: #F5E6C8;
            font-family: 'Philosopher', serif;
            font-size: .78rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all .35s;
        }
        .back-home:hover {
            background: rgba(212, 98, 42, .75);
            border-color: rgba(212, 98, 42, .60);
            color: #fff;
            transform: translateX(-2px);
        }

        /* ── Header ── */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-eyebrow {
            font-family: 'Philosopher', serif;
            font-size: .85rem;
            letter-spacing: .42em;
            text-transform: uppercase;
            color: #FFD580;
            margin-bottom: 1rem;
            text-shadow: 0 1px 8px rgba(0,0,0,.8);
        }
        .login-title {
            font-family: 'Cinzel Decorative', 'Cinzel', serif;
            font-size: 3.2rem;
            font-weight: 700;
            color: #FFFFFF;
            text-shadow: 0 0 50px rgba(212, 98, 42, .7), 0 2px 20px rgba(0,0,0,.9), 0 4px 6px rgba(0,0,0,.8);
            margin-bottom: .7rem;
            letter-spacing: .04em;
        }
        .login-subtitle {
            font-size: 1.25rem;
            font-style: italic;
            font-weight: 400;
            color: #FFE8B0;
            text-shadow: 0 1px 10px rgba(0,0,0,.9);
        }

        /* ── Card ── */
        .login-card {
            width: 100%;
            background: rgba(8, 5, 2, .72);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border: 1px solid rgba(201, 169, 110, .22);
            padding: 2.2rem 2rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 24px 80px rgba(0,0,0,.55), inset 0 1px 0 rgba(255,255,255,.04);
            position: relative;
        }

        /* Corner ornaments */
        .login-card::before,
        .login-card::after {
            content: '';
            position: absolute;
            width: 22px; height: 22px;
            border-color: rgba(201,169,110,.35);
            border-style: solid;
        }
        .login-card::before { top: -1px; left: -1px; border-width: 2px 0 0 2px; }
        .login-card::after  { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; }

        /* ── Fields ── */
        .field-group { margin-bottom: 1.3rem; }
        .field-label {
            display: block;
            font-family: 'Philosopher', serif;
            font-size: .78rem;
            letter-spacing: .28em;
            text-transform: uppercase;
            color: #FFD580;
            margin-bottom: .55rem;
            text-shadow: 0 1px 6px rgba(0,0,0,.7);
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: .9rem;
            top: 50%; transform: translateY(-50%);
            color: rgba(201, 169, 110, .5);
            font-size: .82rem;
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: .88rem .9rem .88rem 2.2rem;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(201, 169, 110, .30);
            color: #FFFFFF;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            outline: none;
            transition: border-color .3s, background .3s;
        }
        .field-input::placeholder { color: rgba(201, 169, 110, .45); font-style: italic; }
        .field-input:focus {
            border-color: rgba(201, 169, 110, .70);
            background: rgba(255, 255, 255, .10);
        }
        .field-error {
            margin-top: .4rem;
            font-size: .78rem;
            color: #F07A3A;
            font-style: italic;
        }

        /* Eye button */
        .eye-btn {
            position: absolute;
            right: .7rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: rgba(201, 169, 110, .45);
            padding: .3rem;
            transition: color .3s;
        }
        .eye-btn:hover { color: rgba(201, 169, 110, .85); }
        .eye-svg { width: 18px; height: 18px; }
        .hidden { display: none; }

        /* ── Meta row ── */
        .login-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.6rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: .5rem;
            cursor: pointer;
            font-size: .92rem;
            color: #FFE0A0;
            font-style: italic;
        }
        .remember-check {
            width: 14px; height: 14px;
            accent-color: #D4622A;
            background: rgba(255,255,255,.05);
        }
        .forgot-link {
            font-size: .92rem;
            font-style: italic;
            color: #FFD580;
            text-decoration: none;
            transition: color .3s;
        }
        .forgot-link:hover { color: #fff; }

        /* ── Submit button ── */
        .login-btn {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #D4622A, #A84010);
            color: #FFFFFF;
            border: none;
            font-family: 'Philosopher', serif;
            font-size: .95rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 24px rgba(212, 98, 42, .55);
            transition: all .35s;
            position: relative;
            overflow: hidden;
        }
        .login-btn::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.12), transparent);
            transform: translateX(-100%);
            transition: transform .55s;
        }
        .login-btn:hover::before { transform: translateX(100%); }
        .login-btn:hover {
            filter: brightness(1.12);
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(212, 98, 42, .55);
        }
        .login-btn:active { transform: scale(.99); }

        /* ── Bottom links ── */
        .login-register {
            font-size: 1rem;
            color: #FFE0A0;
            text-align: center;
            font-style: italic;
            margin-bottom: .8rem;
            text-shadow: 0 1px 8px rgba(0,0,0,.8);
        }
        .login-register a {
            color: #F07A3A;
            text-decoration: none;
            font-weight: 600;
            margin-left: .3rem;
            transition: color .3s;
        }
        .login-register a:hover { color: #fff; }

        .login-quote {
            font-size: .9rem;
            font-style: italic;
            color: rgba(255, 220, 140, .60);
            text-align: center;
            letter-spacing: .06em;
            text-shadow: 0 1px 6px rgba(0,0,0,.7);
        }

        /* ── Animations ── */
        @keyframes up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: none; }
        }
    </style>

    <script>
        function togglePassword() {
            const input     = document.getElementById('password');
            const eyeOpen   = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            const isHidden  = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        }

        // Embers
        (function(){
            const c = document.getElementById('embers');
            for(let i = 0; i < 22; i++){
                const e = document.createElement('div');
                e.className = 'ember';
                e.style.setProperty('--d',  (2.5 + Math.random() * 4) + 's');
                e.style.setProperty('--dl', (Math.random() * 7) + 's');
                e.style.setProperty('--dr', ((Math.random() - .5) * 120) + 'px');
                e.style.left  = (140 + (Math.random() - .5) * 80) + 'px';
                e.style.bottom = (Math.random() * 60) + 'px';
                e.style.background = Math.random() > .5 ? '#F07A3A' : '#FFC878';
                const sz = Math.random() * 3 + 1;
                e.style.width = e.style.height = sz + 'px';
                c.appendChild(e);
            }
        })();
    </script>
</x-guest-layout>