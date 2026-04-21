{{-- resources/views/partials/mini-player.blade.php --}}
{{-- Player : modale desktop + plein écran mobile + visualiseur Three.js --}}

{{-- ── OVERLAY ── --}}
<div id="player-overlay"
     onclick="playerClose()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);
            z-index:498;backdrop-filter:blur(8px);opacity:0;transition:opacity .35s;">
</div>

{{-- ── PLAYER PRINCIPAL ── --}}
<div id="mini-player"
     style="display:none;position:fixed;z-index:499;
            background:linear-gradient(160deg,#0D0A18,#12101E,#0A0C18);
            border:1px solid rgba(201,151,58,.18);
            backdrop-filter:blur(30px);
            transition:all .45s cubic-bezier(.34,1.1,.64,1);">

    {{-- ══ VUE PLEIN ══ --}}
    <div id="player-full">
        

        {{-- Barre drag (mobile) --}}
        <div class="player-drag-bar"></div>

        {{-- Header --}}
        <div class="player-header">
            <div class="player-header-info">
                <p id="player-title-full" class="player-title-full">—</p>
                <p id="player-status-full" class="player-status-full">En attente</p>
            </div>
            <div class="player-header-actions">
                <button onclick="playerMinimize()" class="player-action-btn player-minimize-btn" title="Réduire">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/>
                        <line x1="10" y1="14" x2="21" y2="3"/><line x1="3" y1="21" x2="14" y2="10"/>
                    </svg>
                </button>
                <button onclick="playerClose()" class="player-action-btn" title="Fermer">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ── CANVAS Three.js visualiseur ── --}}
        <div class="player-vis-wrap">
            <canvas id="player-vis-canvas"></canvas>
            {{-- Cercle central (label titre court) --}}
            <div class="player-vis-center">
                <div id="player-vis-bars" class="player-vis-bars">
                    @for($i = 0; $i < 5; $i++)
                        <div class="vis-bar-inner"
                             style="animation-delay:{{ $i * 0.12 }}s"></div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Progression --}}
        <div class="player-progress-wrap">
            <span id="player-current-full" class="player-time">0:00</span>
            <div id="progress-bar-full" class="player-progress-track">
                <div id="progress-fill-full" class="player-progress-fill" style="width:0%"></div>
                <div id="progress-thumb"    class="player-progress-thumb" style="left:0%"></div>
            </div>
            <span id="player-duration-full" class="player-time">—</span>
        </div>

        {{-- Contrôles secondaires --}}
        <div class="player-controls-secondary">
            <button id="btn-shuffle-full" onclick="playerShuffleToggle()" class="player-ctrl-icon" title="Aléatoire">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/>
                    <polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>
                </svg>
            </button>
            <button id="btn-repeat-full" onclick="playerRepeatToggle()" class="player-ctrl-icon" title="Répéter">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="17 1 21 5 17 9"/>
                    <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                    <polyline points="7 23 3 19 7 15"/>
                    <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                </svg>
            </button>
            <div class="player-volume">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" opacity=".45">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                </svg>
                <input id="volume-slider" type="range" min="0" max="1" step="0.05" value="0.8"
                       oninput="playerVolume(this.value)" class="player-volume-range">
            </div>
        </div>

        {{-- Contrôles principaux --}}
        <div class="player-controls-main">
            <button onclick="playerPrev()"    class="player-ctrl-skip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="19,20 9,12 19,4"/>
                    <rect x="4" y="4" width="3" height="16" rx="1"/>
                </svg>
            </button>
            <button onclick="playerRewind()"  class="player-ctrl-skip player-ctrl-sm">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 .49-4.97"/>
                </svg>
                <span>15</span>
            </button>
            <button id="btn-play-pause-full" onclick="playerToggle()" class="player-ctrl-play">
                <svg id="icon-play-full"  width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><polygon points="6,3 20,12 6,21"/></svg>
                <svg id="icon-pause-full" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="5" y="3" width="4" height="18" rx="1"/><rect x="15" y="3" width="4" height="18" rx="1"/></svg>
            </button>
            <button onclick="playerForward()" class="player-ctrl-skip player-ctrl-sm">
                <span>15</span>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-.49-4.97"/>
                </svg>
            </button>
            <button onclick="playerNext()"    class="player-ctrl-skip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="5,4 15,12 5,20"/>
                    <rect x="17" y="4" width="3" height="16" rx="1"/>
                </svg>
            </button>
        </div>

    </div>{{-- fin player-full --}}

    {{-- ══ VUE MINI (desktop) ══ --}}
    <div id="player-mini" style="display:none;">
        <div class="player-mini-inner">
            <div class="flex items-end gap-0.5 h-6 w-7 shrink-0">
                @for($i = 0; $i < 4; $i++)
                    <div class="bar-mini flex-1 rounded-full bg-[#C9973A]"
                         style="height:{{ [40,80,55,90][$i] }}%;
                                animation:bar-bounce .8s ease-in-out infinite;
                                animation-delay:{{ $i * 0.14 }}s;
                                animation-play-state:paused;"></div>
                @endfor
            </div>
            <div class="flex-1 min-w-0 mx-3">
                <p id="player-title-mini"  class="text-[13px] text-[#E8D5A0] truncate">—</p>
                <p id="player-status-mini" class="text-[10px] text-[#C9973A]/50">En pause</p>
            </div>
            <div id="progress-bar-mini" class="player-mini-progress" onclick="playerSeekMini(event)">
                <div id="progress-fill-mini" class="player-mini-fill" style="width:0%"></div>
            </div>
            <button onclick="playerToggle()" class="player-mini-play">
                <svg id="icon-play-mini"  width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
                <svg id="icon-pause-mini" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button onclick="playerNext()"     class="player-mini-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,4 15,12 5,20"/><rect x="17" y="4" width="3" height="16" rx="1"/></svg></button>
            <button onclick="playerMaximize()" class="player-mini-btn" title="Agrandir">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
            </button>
            <button onclick="playerClose()"   class="player-mini-btn opacity-40 hover:opacity-100">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="h-0.5 w-full bg-white/5">
            <div id="progress-fill-mini-bar" class="h-full bg-[#C9973A]/60" style="width:0%"></div>
        </div>
    </div>

</div>{{-- fin mini-player --}}

<style>
/* ════════════════════════════════════════════════════════
   PLAYER CSS
════════════════════════════════════════════════════════ */
.player-drag-bar {
    width:40px;height:4px;background:rgba(201,151,58,.3);
    border-radius:2px;margin:12px auto 0;display:none;
}
.player-header {
    display:flex;align-items:flex-start;justify-content:space-between;
    padding:1.2rem 1.4rem 0;
}
.player-header-info { flex:1;min-width:0; }
.player-title-full {
    font-family:Georgia,serif;font-size:1.05rem;color:#E8D5A0;
    font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    margin-bottom:.2rem;
}
.player-status-full {
    font-size:.68rem;color:rgba(201,151,58,.55);
    letter-spacing:.06em;font-style:italic;
}
.player-header-actions { display:flex;gap:.45rem;margin-left:.8rem;flex-shrink:0; }
.player-action-btn {
    width:28px;height:28px;border-radius:50%;border:1px solid rgba(201,151,58,.15);
    background:rgba(201,151,58,.05);color:rgba(201,151,58,.5);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:all .25s;
}
.player-action-btn:hover { background:rgba(201,151,58,.15);color:#E8D5A0;border-color:rgba(201,151,58,.4); }

/* ── Visualiseur ── */
.player-vis-wrap {
    position:relative;
    display:flex;align-items:center;justify-content:center;
    padding:1rem 0 .8rem;
}
#player-vis-canvas {
    display:block;
    /* taille fixée par JS */
}
.player-vis-center {
    position:absolute;
    top:50%;left:50%;
    transform:translate(-50%,-50%);
    width:62px;height:62px;
    border-radius:50%;
    background:rgba(10,8,20,.85);
    border:1.5px solid rgba(201,151,58,.25);
    display:flex;align-items:center;justify-content:center;
    pointer-events:none;
}
.player-vis-bars { display:flex;align-items:flex-end;gap:2px;height:24px; }
.vis-bar-inner {
    width:3px;border-radius:2px;
    background:#C9973A;
    height:40%;
    animation:bar-bounce .8s ease-in-out infinite;
    animation-play-state:paused;
}

/* ── Progression ── */
.player-progress-wrap {
    display:flex;align-items:center;gap:.65rem;
    padding:0 1.4rem;margin-bottom:.9rem;
}
.player-time { font-size:.68rem;color:rgba(201,151,58,.45);font-family:monospace;flex-shrink:0; }
.player-progress-track {
    flex:1;height:3px;background:rgba(255,255,255,.07);
    border-radius:2px;cursor:pointer;position:relative;
}
.player-progress-fill {
    height:100%;
    background:linear-gradient(90deg,#C9520A,#C9973A);
    border-radius:2px;transition:width .1s linear;
}
.player-progress-thumb {
    position:absolute;top:50%;transform:translate(-50%,-50%);
    width:11px;height:11px;border-radius:50%;background:#C9973A;
    box-shadow:0 0 7px rgba(201,151,58,.6);
    transition:left .1s linear;cursor:grab;
}

/* ── Contrôles secondaires ── */
.player-controls-secondary {
    display:flex;align-items:center;justify-content:center;
    gap:1rem;padding:0 1.4rem;margin-bottom:.8rem;
}
.player-ctrl-icon {
    color:rgba(201,151,58,.35);cursor:pointer;
    transition:color .2s;background:none;border:none;padding:3px;
}
.player-ctrl-icon:hover,.player-ctrl-icon.active { color:#C9973A; }
.player-volume { display:flex;align-items:center;gap:.35rem; }
.player-volume-range { width:65px;accent-color:#C9973A;cursor:pointer; }

/* ── Contrôles principaux ── */
.player-controls-main {
    display:flex;align-items:center;justify-content:center;
    gap:.65rem;padding:0 1.4rem 1.6rem;
}
.player-ctrl-skip {
    width:38px;height:38px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    color:rgba(201,151,58,.5);background:none;border:none;
    cursor:pointer;transition:all .2s;
}
.player-ctrl-skip:hover { color:#E8D5A0;background:rgba(201,151,58,.08); }
.player-ctrl-sm {
    flex-direction:column;gap:1px;
    font-size:8px;font-family:monospace;
    color:rgba(201,151,58,.4);
}
.player-ctrl-sm:hover { color:#C9973A; }
.player-ctrl-play {
    width:58px;height:58px;border-radius:50%;
    background:linear-gradient(135deg,#C9520A,#C9973A);
    border:none;
    display:flex;align-items:center;justify-content:center;
    color:#fff;cursor:pointer;
    box-shadow:0 5px 20px rgba(201,151,58,.4);
    transition:all .25s;flex-shrink:0;
}
.player-ctrl-play:hover { transform:scale(1.08);box-shadow:0 8px 28px rgba(201,151,58,.55); }
.player-ctrl-play:active { transform:scale(.95); }

/* ── Mini ── */
.player-mini-inner {
    display:flex;align-items:center;padding:.6rem .9rem;gap:.55rem;
}
.player-mini-progress {
    width:75px;height:3px;background:rgba(255,255,255,.07);
    border-radius:2px;cursor:pointer;position:relative;flex-shrink:0;
}
.player-mini-fill { height:100%;background:#C9973A;border-radius:2px;transition:width .1s linear; }
.player-mini-play {
    width:26px;height:26px;border-radius:50%;
    background:linear-gradient(135deg,#C9520A,#C9973A);
    border:none;display:flex;align-items:center;justify-content:center;
    color:#fff;cursor:pointer;flex-shrink:0;transition:transform .2s;
}
.player-mini-play:hover { transform:scale(1.1); }
.player-mini-btn {
    width:26px;height:26px;display:flex;align-items:center;justify-content:center;
    color:rgba(201,151,58,.5);background:none;border:none;
    cursor:pointer;transition:color .2s;flex-shrink:0;
}
.player-mini-btn:hover { color:#C9973A; }

/* ── Keyframes ── */
@keyframes bar-bounce { 0%,100%{transform:scaleY(.35)} 50%{transform:scaleY(1)} }

/* ════════════════════════════════════════════════════════
   LAYOUT DESKTOP
════════════════════════════════════════════════════════ */
@media (min-width:769px) {
    #mini-player.player-mode-full {
        top:50%;left:50%;
        transform:translate(-50%,-50%) scale(.9);
        width:360px;border-radius:28px;opacity:0;
    }
    #mini-player.player-mode-full.player-visible {
        transform:translate(-50%,-50%) scale(1);opacity:1;
    }
    #mini-player.player-mode-mini {
        bottom:1.4rem;right:1.4rem;
        width:340px;border-radius:16px;
        opacity:0;transform:translateY(10px);
    }
    #mini-player.player-mode-mini.player-visible { opacity:1;transform:translateY(0); }
    .player-drag-bar  { display:none!important; }
    .player-minimize-btn { display:flex!important; }
}

/* ════════════════════════════════════════════════════════
   LAYOUT MOBILE
════════════════════════════════════════════════════════ */
@media (max-width:768px) {
    #mini-player {
        position:fixed!important;
        left:0!important;right:0!important;bottom:0!important;top:auto!important;
        width:100%!important;
        border-radius:28px 28px 0 0!important;
        border-left:none!important;border-right:none!important;border-bottom:none!important;
        transform:translateY(100%)!important;opacity:1!important;
    }
    #mini-player.player-visible { transform:translateY(0)!important; }
    .player-drag-bar { display:block!important; }
    .player-minimize-btn { display:none!important; }
    #player-mini { display:none!important; }
    .player-volume { display:none!important; }
    #player-overlay { display:none!important; }
    body.player-open { overflow:hidden; }
}
</style>

{{-- Three.js + Simplex Noise + Howler --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
<script>
// SimplexNoise minimal (adapté du code original fourni)
class SimplexNoise {
    constructor() {
        this.p = new Uint8Array(256);
        for (let i = 0; i < 256; i++) this.p[i] = i;
        for (let i = 255; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [this.p[i], this.p[j]] = [this.p[j], this.p[i]];
        }
        this.perm = new Uint8Array(512);
        for (let i = 0; i < 512; i++) this.perm[i] = this.p[i & 255];
    }
    dot(g, x, y) { return g[0] * x + g[1] * y; }
    noise2D(xin, yin) {
        const grad3 = [[1,1],[-1,1],[1,-1],[-1,-1],[1,0],[-1,0],[0,1],[0,-1],[1,1],[-1,1],[1,-1],[-1,-1]];
        const F2 = 0.5 * (Math.sqrt(3) - 1);
        const G2 = (3 - Math.sqrt(3)) / 6;
        const s = (xin + yin) * F2;
        const i = Math.floor(xin + s), j = Math.floor(yin + s);
        const t = (i + j) * G2;
        const x0 = xin - i + t, y0 = yin - j + t;
        const [i1, j1] = x0 > y0 ? [1, 0] : [0, 1];
        const x1 = x0 - i1 + G2, y1 = y0 - j1 + G2;
        const x2 = x0 - 1 + 2 * G2, y2 = y0 - 1 + 2 * G2;
        const ii = i & 255, jj = j & 255;
        const gi0 = this.perm[ii + this.perm[jj]] % 12;
        const gi1 = this.perm[ii + i1 + this.perm[jj + j1]] % 12;
        const gi2 = this.perm[ii + 1 + this.perm[jj + 1]] % 12;
        let n0 = 0, n1 = 0, n2 = 0;
        let t0 = 0.5 - x0*x0 - y0*y0;
        if (t0 >= 0) { t0 *= t0; n0 = t0 * t0 * this.dot(grad3[gi0], x0, y0); }
        let t1 = 0.5 - x1*x1 - y1*y1;
        if (t1 >= 0) { t1 *= t1; n1 = t1 * t1 * this.dot(grad3[gi1], x1, y1); }
        let t2 = 0.5 - x2*x2 - y2*y2;
        if (t2 >= 0) { t2 *= t2; n2 = t2 * t2 * this.dot(grad3[gi2], x2, y2); }
        return 70 * (n0 + n1 + n2);
    }
}

// ════════════════════════════════════════════════════════════════
// VISUALISEUR THREE.JS
// ════════════════════════════════════════════════════════════════
let visRenderer, visScene, visCamera, visMesh, visAnimId;
let simplex = null;
let visActive = false;

// Données audio injectées depuis AudioContext
let audioEnergy = 0;   // volume RMS 0..1
let audioBass   = 0;   // basses fréquences 0..1
let audioMid    = 0;   // mediums 0..1

function initVisualizer() {
    const canvas = document.getElementById('player-vis-canvas');
    const size   = window.innerWidth <= 768 ? 320 : 320;
    canvas.width  = size;
    canvas.height = Math.round(size * 0.52); // ratio paysage doux

    visRenderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    visRenderer.setSize(canvas.width, canvas.height);
    visRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    visRenderer.setClearColor(0x000000, 0); // fond transparent

    // ── Caméra vue de face légèrement plongeante ──
    // ← fov 60 = champ large, cameraZ 55 = distance
    const aspect = canvas.width / canvas.height;
    visCamera = new THREE.PerspectiveCamera(60, aspect, 0.1, 1000);
    visCamera.position.set(0, 12, 55);  // ← Y=12 = légère plongée
    visCamera.lookAt(0, 0, 0);

    visScene = new THREE.Scene();

    // ── 4 lumières colorées qui orbitent (code original) ──
    // ← Changer les hex pour des couleurs différentes
    const lightDefs = [
        { color: 0x0E09DC, intensity: 1.1 }, // bleu électrique
        { color: 0x1CD1E1, intensity: 1.1 }, // cyan
        { color: 0x18C02C, intensity: 1.1 }, // vert
        { color: 0xee3bcf, intensity: 1.1 }, // rose/violet
    ];
    window._visLights = lightDefs.map(def => {
        const l = new THREE.PointLight(def.color, def.intensity, 300);
        visScene.add(l);
        return l;
    });

    // ── Plan ondulé — même approche que l'original ──
    // ← wWidth/wHeight : taille du plan (plus grand = déborde plus)
    // ← segments (3ème/4ème param) : densité de vertices
    //    plus élevé = ondes plus fines et détaillées
    const wWidth  = 100;
    const wHeight = 60;
    const geo = new THREE.PlaneBufferGeometry(wWidth, wHeight, wWidth / 2, wHeight / 2);
    const mat = new THREE.MeshLambertMaterial({ color: 0xffffff, side: THREE.DoubleSide });
    visMesh = new THREE.Mesh(geo, mat);

    // ← Inclinaison du plan — comme dans l'original
    // -Math.PI/2 = à plat horizontal, -.2 = légèrement relevé devant
    visMesh.rotation.x = -Math.PI / 2 - 0.2;
    visMesh.position.y = -8; // ← descendre le plan dans la vue
    visScene.add(visMesh);

    simplex = new SimplexNoise();
    visActive = true;
    tickVisualizer();
}

function tickVisualizer() {
    if (!visActive) return;
    visAnimId = requestAnimationFrame(tickVisualizer);

    const time = Date.now() * 0.0002; // ← vitesse de défilement des ondes

    // ── Paramètres audio → géométrie ──
    // xyCoef : finesse des ondes
    // ← grand = ondes larges et lentes, petit = ondes serrées
    const xyCoef = 50 - audioBass * 35;  // 15..50

    // zCoef : amplitude (hauteur) des vagues
    // ← augmenter le multiplicateur pour des vagues plus hautes
    const zCoef  = 8 + audioEnergy * 14; // 8..22

    // ── Déplacer chaque vertex Z (comme animatePlane() original) ──
    const pos = visMesh.geometry.attributes.position;
    const arr = pos.array;
    for (let i = 0; i < arr.length; i += 3) {
        const x = arr[i];
        const y = arr[i + 1];
        // noise2D = bruit lisse 2D → valeur -1..1
        arr[i + 2] = simplex.noise2D(
            x / xyCoef,
            y / xyCoef + time          // ← + time = les ondes avancent
        ) * zCoef;
    }
    pos.needsUpdate = true;

    // ── Animer les lumières en orbite (animateLights() original) ──
    const t   = Date.now() * 0.001;
    const d   = 40 + audioEnergy * 20; // ← rayon orbite des lumières
    const y   = 8  + audioEnergy * 6;  // ← hauteur des lumières
    window._visLights[0].position.set(Math.sin(t * 0.1) * d, y, Math.cos(t * 0.2) * d);
    window._visLights[1].position.set(Math.cos(t * 0.3) * d, -y, Math.sin(t * 0.4) * d);
    window._visLights[2].position.set(Math.sin(t * 0.5) * d, y, Math.sin(t * 0.6) * d);
    window._visLights[3].position.set(Math.sin(t * 0.7) * d, y, Math.cos(t * 0.8) * d);

    // Intensité pulsée sur le rythme
    window._visLights.forEach(l => {
        l.intensity = 1.0 + audioEnergy * 1.5;
    });

    visRenderer.render(visScene, visCamera);
}

function destroyVisualizer() {
    visActive = false;
    cancelAnimationFrame(visAnimId);
    if (visRenderer) { visRenderer.dispose(); visRenderer = null; }
    if (visMesh) {
        visMesh.geometry.dispose();
        visMesh.material.dispose();
        visMesh = null;
    }
    visScene = null;
    visCamera = null;
}

function pauseVisualizer() {
    // Mode mini → ralentir (juste réduire l'énergie)
    visActive = false;
    cancelAnimationFrame(visAnimId);
}

function resumeVisualizer() {
    if (!visRenderer) return;
    visActive = true;
    tickVisualizer();
}

// ════════════════════════════════════════════════════════════════
// AUDIO CONTEXT — analyse des fréquences en temps réel
// ════════════════════════════════════════════════════════════════
let audioCtx    = null;
let analyser    = null;
let freqData    = null;
let audioSource = null;
let audioRafId  = null;

function initAudioAnalyser(howlSound) {
    // Récupérer le nœud Howler → AudioContext
    if (!howlSound) return;

    // Howler expose son contexte
    if (!audioCtx) {
        audioCtx = Howler.ctx;
    }

    // Créer l'analyseur
    if (!analyser) {
        analyser = audioCtx.createAnalyser();
        analyser.fftSize       = 256;
        analyser.smoothingTimeConstant = 0.78;
        freqData = new Uint8Array(analyser.frequencyBinCount);
    }

    // Connecter la source Howler → analyser → destination
    try {
        // Howler._sounds contient les nodes internes
        const node = howlSound._sounds[0]?._node;
        if (node && !audioSource) {
            audioSource = audioCtx.createMediaElementSource(node);
            audioSource.connect(analyser);
            analyser.connect(audioCtx.destination);
        }
    } catch (e) {
        // Sur iOS le nœud peut ne pas être exposé — on continue sans analyse
        console.warn('AudioContext node unavailable:', e);
    }

    tickAudioAnalysis();
}

function tickAudioAnalysis() {
    if (!analyser) return;
    audioRafId = requestAnimationFrame(tickAudioAnalysis);

    analyser.getByteFrequencyData(freqData);
    const len = freqData.length;

    // RMS global (énergie totale)
    let sum = 0;
    for (let i = 0; i < len; i++) sum += freqData[i] * freqData[i];
    audioEnergy = Math.sqrt(sum / len) / 255;

    // Basses (0..10% des bins)
    let bassSum = 0, bassCount = Math.floor(len * 0.1);
    for (let i = 0; i < bassCount; i++) bassSum += freqData[i];
    audioBass = (bassSum / bassCount) / 255;

    // Médiums (10..40%)
    let midSum = 0, midCount = Math.floor(len * 0.3);
    for (let i = bassCount; i < bassCount + midCount; i++) midSum += freqData[i];
    audioMid = (midSum / midCount) / 255;
}

function stopAudioAnalysis() {
    cancelAnimationFrame(audioRafId);
    audioEnergy = 0;
    audioBass   = 0;
    audioMid    = 0;
}

// ════════════════════════════════════════════════════════════════
// HOWLER + PLAYER
// ════════════════════════════════════════════════════════════════
let sound        = null;
let currentSlug  = null;
let currentIndex = 0;
let playlist     = [];
let rafId        = null;
let isShuffle    = false;
let isRepeat     = false;
let isMinimized  = false;
const isMobilePlayer = () => window.innerWidth <= 768;

window.addEventListener('play-track', e => {
    const d = e.detail;
    if (d.playlist && d.playlist.length) {
        playlist     = d.playlist;
        currentIndex = d.index ?? 0;
    }
    loadTrack(d.slug, d.title);
});

function loadTrack(slug, title) {
    stopAudioAnalysis();
    if (sound) { sound.stop(); sound.unload(); cancelAnimationFrame(rafId); }
    currentSlug = slug;
    window.dispatchEvent(new CustomEvent('track-changed', { detail: { slug } }));

    playerShow();
    ['player-title-full','player-title-mini'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = title;
    });
    setStatus('Chargement...');
    setProgress(0);
    setIcons(false);

    sound = new Howl({
        src:   ['/api/audio/' + slug],
        html5: true,
        volume: parseFloat(document.getElementById('volume-slider')?.value || .8),

        onload() {
            document.getElementById('player-duration-full').textContent = formatTime(sound.duration());
            // Initialiser l'analyseur audio une fois chargé
            initAudioAnalyser(sound);
        },
        onplay() {
            setStatus('En cours de lecture');
            setIcons(true);
            startProgress();
            // Barres visualiseur central
            document.querySelectorAll('.vis-bar-inner').forEach(b => b.style.animationPlayState = 'running');
            setBarsState(true);
        },
        onpause() {
            setStatus('En pause');
            setIcons(false);
            cancelAnimationFrame(rafId);
            document.querySelectorAll('.vis-bar-inner').forEach(b => b.style.animationPlayState = 'paused');
            setBarsState(false);
        },
        onstop() {
            setIcons(false);
            cancelAnimationFrame(rafId);
            setProgress(0);
            setBarsState(false);
        },
        onend() {
            cancelAnimationFrame(rafId);
            setBarsState(false);
            isRepeat ? (sound.seek(0), sound.play()) : playerNext();
        },
        onloaderror(id, err) {
            console.error('Audio load error:', err);
            setStatus('Erreur de chargement');
        },
        onplayerror(id, err) {
            console.error('Audio play error:', err);
            sound.once('unlock', () => sound.play());
        },
    });
    sound.play();
}

// ── Afficher le player ────────────────────────────────────────
function playerShow() {
    const player  = document.getElementById('mini-player');
    const overlay = document.getElementById('player-overlay');
    const full    = document.getElementById('player-full');
    const mini    = document.getElementById('player-mini');

    isMinimized = false;
    player.style.display = 'block';
    full.style.display   = 'block';
    mini.style.display   = 'none';
    player.classList.remove('player-mode-mini');
    player.classList.add('player-mode-full');

    if (!isMobilePlayer()) {
        overlay.style.display = 'block';
        setTimeout(() => overlay.style.opacity = '1', 10);
    } else {
        document.body.classList.add('player-open');
    }

    setTimeout(() => player.classList.add('player-visible'), 10);

    // Init ou reprendre le visualiseur
    if (!visRenderer) {
        initVisualizer();
    } else {
        resumeVisualizer();
    }
}

function playerMinimize() {
    if (isMobilePlayer()) return;
    const player  = document.getElementById('mini-player');
    const overlay = document.getElementById('player-overlay');
    const full    = document.getElementById('player-full');
    const mini    = document.getElementById('player-mini');

    isMinimized = true;
    // Pause visualiseur pour économiser les ressources
    pauseVisualizer();

    overlay.style.opacity = '0';
    setTimeout(() => overlay.style.display = 'none', 350);
    player.classList.remove('player-mode-full','player-visible');

    setTimeout(() => {
        full.style.display = 'none';
        mini.style.display = 'block';
        player.classList.add('player-mode-mini');
        setTimeout(() => player.classList.add('player-visible'), 10);
    }, 200);
}

function playerMaximize() {
    const player  = document.getElementById('mini-player');
    const overlay = document.getElementById('player-overlay');
    const full    = document.getElementById('player-full');
    const mini    = document.getElementById('player-mini');

    isMinimized = false;
    mini.style.display = 'none';
    full.style.display = 'block';
    player.classList.remove('player-mode-mini','player-visible');
    overlay.style.display = 'block';
    setTimeout(() => overlay.style.opacity = '1', 10);
    setTimeout(() => {
        player.classList.add('player-mode-full');
        setTimeout(() => player.classList.add('player-visible'), 10);
    }, 50);

    // Reprendre le visualiseur
    resumeVisualizer();
}

function playerClose() {
    stopAudioAnalysis();
    if (sound) { sound.stop(); sound.unload(); sound = null; }
    cancelAnimationFrame(rafId);
    pauseVisualizer();

    const player  = document.getElementById('mini-player');
    const overlay = document.getElementById('player-overlay');
    player.classList.remove('player-visible');
    overlay.style.opacity = '0';

    setTimeout(() => {
        player.style.display  = 'none';
        overlay.style.display = 'none';
        player.classList.remove('player-mode-full','player-mode-mini');
        document.body.classList.remove('player-open');
    }, 450);

    currentSlug = null;
    window.dispatchEvent(new CustomEvent('track-changed', { detail: { slug: null } }));
}

// ── Contrôles ─────────────────────────────────────────────────
function playerToggle()  { if (!sound) return; sound.playing() ? sound.pause() : sound.play(); }
function playerRewind()  { if (!sound) return; sound.seek(Math.max(0, sound.seek() - 15)); }
function playerForward() { if (!sound) return; sound.seek(Math.min(sound.duration(), sound.seek() + 15)); }
function playerVolume(v) { if (sound) sound.volume(parseFloat(v)); }

function playerNext() {
    if (!playlist.length) return;
    currentIndex = isShuffle
        ? Math.floor(Math.random() * playlist.length)
        : (currentIndex + 1) % playlist.length;
    const t = playlist[currentIndex];
    window.dispatchEvent(new CustomEvent('player-next'));
    loadTrack(t.slug, t.title);
}
function playerPrev() {
    if (!playlist.length) return;
    if (sound && sound.seek() > 3) { sound.seek(0); return; }
    currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;
    const t = playlist[currentIndex];
    window.dispatchEvent(new CustomEvent('player-prev'));
    loadTrack(t.slug, t.title);
}

function playerShuffleToggle() {
    isShuffle = !isShuffle;
    const el = document.getElementById('btn-shuffle-full');
    if (el) el.style.color = isShuffle ? '#C9973A' : '';
}
function playerRepeatToggle() {
    isRepeat = !isRepeat;
    const el = document.getElementById('btn-repeat-full');
    if (el) el.style.color = isRepeat ? '#C9973A' : '';
}

function playerSeekFull(e) {
    if (!sound) return;
    const bar = document.getElementById('progress-bar-full');
    const pct = (e.clientX - bar.getBoundingClientRect().left) / bar.offsetWidth;
    sound.seek(Math.max(0, pct) * sound.duration());
}
function playerSeekMini(e) {
    if (!sound) return;
    const bar = document.getElementById('progress-bar-mini');
    const pct = (e.clientX - bar.getBoundingClientRect().left) / bar.offsetWidth;
    sound.seek(Math.max(0, pct) * sound.duration());
}

// ── Progression ───────────────────────────────────────────────
function startProgress() {
    function tick() {
        if (!sound || !sound.playing()) return;
        const seek = sound.seek() || 0;
        const dur  = sound.duration() || 1;
        setProgress(seek / dur * 100);
        document.getElementById('player-current-full').textContent = formatTime(seek);
        rafId = requestAnimationFrame(tick);
    }
    tick();
}
function setProgress(pct) {
    const p = pct + '%';
    ['progress-fill-full','progress-fill-mini','progress-fill-mini-bar'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.width = p;
    });
    const thumb = document.getElementById('progress-thumb');
    if (thumb) thumb.style.left = p;
}

function setIcons(playing) {
    [['icon-play-full','icon-pause-full'],['icon-play-mini','icon-pause-mini']].forEach(([p,pa]) => {
        const pe  = document.getElementById(p);
        const pae = document.getElementById(pa);
        if (pe)  pe.style.display  = playing ? 'none'  : 'block';
        if (pae) pae.style.display = playing ? 'block' : 'none';
    });
}
function setStatus(txt) {
    ['player-status-full','player-status-mini'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = txt;
    });
}
function setBarsState(playing) {
    document.querySelectorAll('.bar-mini').forEach(b => {
        b.style.animationPlayState = playing ? 'running' : 'paused';
    });
}
function formatTime(secs) {
    const m = Math.floor(secs / 60);
    const s = Math.floor(secs % 60).toString().padStart(2,'0');
    return m + ':' + s;
}
// ── Seek drag sur la barre de progression ─────────────────────
(function() {
    const bar   = document.getElementById('progress-bar-full');
    let dragging = false;

    function seekTo(e) {
        if (!sound) return;
        // Supporte souris et touch
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const rect = bar.getBoundingClientRect();
        const pct  = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
        sound.seek(pct * sound.duration());
        setProgress(pct * 100);
    }

    // ── Souris ──
    bar.addEventListener('mousedown', e => {
        dragging = true;
        seekTo(e);
    });
    window.addEventListener('mousemove', e => {
        if (!dragging) return;
        seekTo(e);
    });
    window.addEventListener('mouseup', () => { dragging = false; });

    // ── Touch (mobile) ──
    bar.addEventListener('touchstart', e => {
        dragging = true;
        seekTo(e);
    }, { passive: true });
    window.addEventListener('touchmove', e => {
        if (!dragging) return;
        seekTo(e);
    }, { passive: true });
    window.addEventListener('touchend', () => { dragging = false; });

})();
</script>