{{--
    resources/views/partials/welcome-video.blade.php

    @include('partials.welcome-video', [
        'videoUrl'   => asset('videos/welcome.mp4'),
        'posterUrl'  => asset('images/video-poster.jpg'),
        'storageKey' => 'rs_welcome_seen_' . auth()->id(),
    ])
--}}
@php
    $storageKey = $storageKey ?? 'rs_welcome_seen';
    $posterUrl  = $posterUrl  ?? '';
@endphp

{{-- VIDEO.JS CDN --}}
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>

<div class="wv-wrap">

    {{-- Header --}}
    <div class="wv-header">
        <div class="wv-header-left">
            <span class="wv-dot"></span>
            <span class="wv-dot wv-dot--2"></span>
            <span class="wv-dot wv-dot--3"></span>
        </div>
        <div class="wv-header-title">
            <span class="wv-icon">✦</span>
            Message de bienvenue
        </div>
        <div class="wv-header-right">
            <span id="wv-badge" class="wv-badge">Nouveau</span>
        </div>
    </div>

    {{-- Player --}}
    <div class="wv-player-wrap">
        {{-- Glow derrière --}}
        <div class="wv-glow"></div>

        <video id="wv-player"
               class="video-js vjs-big-play-centered"
               @if($posterUrl) poster="{{ $posterUrl }}" @endif
               preload="auto"
               playsinline>
            <source src="{{ $videoUrl }}" type="video/mp4" />
        </video>
    </div>

    {{-- Footer --}}
    <div class="wv-footer">
        <label class="wv-check-label">
            <input id="wv-no-replay" type="checkbox" class="wv-check" />
            <span class="wv-check-box"></span>
            <span class="wv-check-text">Ne plus lancer automatiquement</span>
        </label>
        <span id="wv-status" class="wv-status"></span>
    </div>
</div>

{{-- Séparateur --}}
<div class="wv-sep"></div>

<script>
(function () {
    const STORAGE_KEY = {{ Js::from($storageKey) }};
    const alreadySeen = localStorage.getItem(STORAGE_KEY) === 'seen';

    const chk    = document.getElementById('wv-no-replay');
    const badge  = document.getElementById('wv-badge');
    const status = document.getElementById('wv-status');

    if (alreadySeen) {
        badge.style.opacity  = '0';
        chk.checked          = true;
        status.textContent   = 'Cliquez ▶ pour lancer';
    }

    const player = videojs('wv-player', {
        controls:      true,
        fluid:         true,
        muted:         false,
        autoplay:      false,
        responsive:    true,
        aspectRatio:   '16:9',
        playbackRates: [0.75, 1, 1.25, 1.5],
        controlBar: {
            children: [
                'playToggle','volumePanel','currentTimeDisplay',
                'timeDivider','durationDisplay','progressControl',
                'playbackRateMenuButton','fullscreenToggle',
            ],
        },
    });

    if (!alreadySeen) {
        player.ready(function () {
            player.play().catch(function () {
                player.muted(true);
                player.play();
                status.textContent = '🔇 Son coupé — cliquez 🔊 pour activer';
            });
        });
    }

    player.on('play', function () {
        localStorage.setItem(STORAGE_KEY, 'seen');
        badge.style.opacity = '0';
        chk.checked = true;
    });

    player.on('ended', function () {
        status.textContent = '✓ Terminé';
    });

    chk.addEventListener('change', function () {
        if (this.checked) {
            localStorage.setItem(STORAGE_KEY, 'seen');
        } else {
            localStorage.removeItem(STORAGE_KEY);
            badge.style.opacity = '1';
            status.textContent  = '';
        }
    });
})();
</script>

<style>
/* ══════════════════════════════════
   WRAPPER
══════════════════════════════════ */
.wv-wrap {
    margin: 24px 24px 0;
    max-width: 860px;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(145deg, #0D1520 0%, #0A1018 60%, #0D1420 100%);
    border: 1px solid rgba(201,151,58,.18);
    box-shadow:
        0 0 0 1px rgba(201,151,58,.06),
        0 24px 64px rgba(0,0,0,.6),
        inset 0 1px 0 rgba(201,151,58,.08);
}

/* ══ HEADER ══ */
.wv-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    border-bottom: 1px solid rgba(201,151,58,.1);
    background: rgba(255,255,255,.02);
}
.wv-header-left { display: flex; align-items: center; gap: 7px; }
.wv-dot {
    width: 11px; height: 11px; border-radius: 50%;
    background: rgba(255,255,255,.08);
}
.wv-dot--2 { background: rgba(201,151,58,.25); }
.wv-dot--3 { background: rgba(201,151,58,.5); }

.wv-header-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 13px;
    font-weight: 400;
    letter-spacing: .12em;
    color: rgba(232,213,160,.75);
    display: flex;
    align-items: center;
    gap: 7px;
}
.wv-icon {
    color: #C9973A;
    font-size: 11px;
}
.wv-header-right { min-width: 60px; display: flex; justify-content: flex-end; }
.wv-badge {
    font-size: 9px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: #C9973A;
    border: 1px solid rgba(201,151,58,.35);
    padding: 2px 8px;
    border-radius: 20px;
    background: rgba(201,151,58,.06);
    transition: opacity .4s;
}

/* ══ PLAYER ══ */
.wv-player-wrap {
    position: relative;
    background: #000;
}
.wv-glow {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    background:
        radial-gradient(ellipse 60% 40% at 20% 80%, rgba(201,151,58,.07) 0%, transparent 70%),
        radial-gradient(ellipse 40% 30% at 80% 20%, rgba(201,151,58,.05) 0%, transparent 70%);
}
#wv-player.video-js {
    position: relative;
    z-index: 1;
    width: 100% !important;
    font-family: 'Jost', sans-serif;
}

/* ══ FOOTER ══ */
.wv-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 18px;
    border-top: 1px solid rgba(201,151,58,.08);
    background: rgba(0,0,0,.15);
}
.wv-check-label {
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
}
.wv-check { display: none; }
.wv-check-box {
    width: 14px; height: 14px;
    border: 1px solid rgba(201,151,58,.3);
    border-radius: 3px;
    background: transparent;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background .2s, border-color .2s;
    position: relative;
}
.wv-check:checked + .wv-check-box {
    background: rgba(201,151,58,.15);
    border-color: #C9973A;
}
.wv-check:checked + .wv-check-box::after {
    content: '';
    display: block;
    width: 7px; height: 4px;
    border-left: 1.5px solid #C9973A;
    border-bottom: 1.5px solid #C9973A;
    transform: rotate(-45deg) translateY(-1px);
}
.wv-check-text {
    font-size: 11px;
    letter-spacing: .06em;
    color: rgba(200,192,176,.35);
    transition: color .2s;
    user-select: none;
}
.wv-check-label:hover .wv-check-text { color: rgba(200,192,176,.6); }
.wv-status {
    font-size: 11px;
    font-style: italic;
    color: rgba(200,192,176,.28);
    transition: color .4s;
}

/* ══ SÉPARATEUR ══ */
.wv-sep {
    max-width: 860px;
    margin: 20px 24px 0;
    height: 1px;
    background: linear-gradient(90deg,
        transparent 0%,
        rgba(201,151,58,.12) 30%,
        rgba(201,151,58,.12) 70%,
        transparent 100%
    );
}

/* ══ THÈME VIDEO.JS ══ */
#wv-player .vjs-control-bar {
    background: linear-gradient(0deg, rgba(5,8,14,.97) 0%, rgba(5,8,14,.0) 100%);
    padding: 0 4px;
}
#wv-player .vjs-play-progress,
#wv-player .vjs-volume-level { background: #C9973A; }
#wv-player .vjs-play-progress::before { color: #C9973A; }
#wv-player .vjs-slider { background: rgba(255,255,255,.1); border-radius: 2px; }
#wv-player .vjs-load-progress { background: rgba(201,151,58,.15); }

#wv-player .vjs-big-play-button {
    border: 1.5px solid rgba(201,151,58,.7);
    background: rgba(8,12,18,.8);
    border-radius: 50%;
    width: 60px; height: 60px;
    line-height: 60px;
    margin-top: -30px; margin-left: -30px;
    backdrop-filter: blur(10px);
    transition: all .3s;
    box-shadow: 0 0 30px rgba(201,151,58,.2);
}
#wv-player:hover .vjs-big-play-button {
    background: rgba(201,151,58,.15);
    border-color: #C9973A;
    box-shadow: 0 0 40px rgba(201,151,58,.35);
    transform: scale(1.06);
}
#wv-player .vjs-big-play-button .vjs-icon-placeholder::before {
    font-size: 1.6rem;
    line-height: 60px;
    color: #C9973A;
}
#wv-player .vjs-current-time,
#wv-player .vjs-duration,
#wv-player .vjs-time-divider {
    display: flex !important;
    font-size: 11px;
    color: rgba(200,192,176,.5);
    padding: 0 3px;
}
#wv-player .vjs-playback-rate .vjs-playback-rate-value {
    font-size: 11px;
    color: rgba(200,192,176,.5);
    line-height: 3em;
}
#wv-player .vjs-menu-button-popup .vjs-menu .vjs-menu-content {
    background: #0D1520;
    border: 1px solid rgba(201,151,58,.2);
    border-radius: 8px;
}
#wv-player .vjs-menu li { font-size: 12px; }
#wv-player .vjs-menu li.vjs-selected { color: #C9973A; }

@media (max-width: 640px) {
    .wv-wrap {
        margin: 12px 8px 0;
        height: 500px;
    }
   
   
    .wv-player-wrap {
        height: 420px;
    }
    #wv-player.video-js {
        height: 420px !important;
    }
}



</style>