@extends('layouts.app')

@section('title','Renait-Sens — L\'Odyssée du Désert')


@push("styles")
  @vite(['resources/css/odysse_desert.css', 'resources/css/widget_AI.css'])
@endpush

@section('content')
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursor-ring"></div>

    <!-- ══ MODE SWITCHER ══ -->
    <div class="mode-sw">
      <button class="msw on" data-m="night" onclick="setMode_odysse('night')">🌙 Nuit</button>
      <button class="msw"    data-m="dawn"  onclick="setMode_odysse('dawn')">🌅 Aube</button>
      <button class="msw"    data-m="noon"  onclick="setMode_odysse('noon')">🌞 Midi</button>
    </div>

    <x-header/>

    <!-- ══ HERO ══ -->
    <div class="mobile-nav" id="mobile-nav">
      <a href="#appel"      onclick="closeMenu()">L'Appel</a>
      <div class="mnav-div"></div>
      <a href="#traversees" onclick="closeMenu()">Les Traversées</a>
      <div class="mnav-div"></div>
      <a href="#bulle"      onclick="closeMenu()">La Bulle</a>
      <div class="mnav-div"></div>
      <a href="#djanet"     onclick="closeMenu()">Djanet</a>
      <div class="mnav-div"></div>
      <a href="{{route("login")}}" style="font-size:1rem;letter-spacing:.2em;margin-top:1rem;color:var(--acc)">Entre dans l'odyssée</a>
    </div>

<!-- ══ HERO ══ -->
<section id="hero">
  <div class="hero-sky"></div>
  <div class="hero-bg-texture" id="hero-parallax"></div>
  <div class="hero-overlay"></div>
  <canvas id="hero-canvas"></canvas>
  <div class="hero-dunes">
    <svg viewBox="0 0 1440 300" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="du1" x1="0" y1="0" x2="0" y2="1">
          <stop id="du1a" offset="0%" stop-color="#1A1008" stop-opacity=".85"/>
          <stop id="du1b" offset="100%" stop-color="#0D0D1A"/>
        </linearGradient>
        <linearGradient id="du2" x1="0" y1="0" x2="0" y2="1">
          <stop id="du2a" offset="0%" stop-color="#241A0A" stop-opacity=".9"/>
          <stop id="du2b" offset="100%" stop-color="#0A0A12"/>
        </linearGradient>
      </defs>
      <path d="M0,185 Q200,100 400,155 Q600,205 800,125 Q1000,45 1200,115 Q1365,168 1440,135 L1440,300 L0,300Z" fill="url(#du1)" opacity=".55"/>
      <path d="M0,215 Q180,148 360,190 Q540,235 720,168 Q900,102 1100,168 Q1302,225 1440,188 L1440,300 L0,300Z" fill="url(#du2)" opacity=".9"/>
      <path id="dcrest" d="M0,217 Q180,150 360,192 Q540,237 720,170 Q900,104 1100,170 Q1302,227 1440,190" fill="none" stroke="#C9A96E" stroke-width=".65" opacity=".32"/>
    </svg>
  </div>

  <div class="hero-content">
    <span class="h-eyebrow">Renait-Sens · L'Odyssée du Désert</span>
    <h1 class="h-title">
      L'Ombre du Tassili
      <em id="hero-sub">Là où le silence parle</em>
    </h1>
    <div class="h-div"><span></span><em id="hero-icon">🌙</em><span></span></div>
    <p class="h-slogan">
      <span class="slogan-t sl-night">Le désert n'a pas de fin...<br>Il commence là où tu poses ton regard.</span>
      <span class="slogan-t sl-dawn">L'aube repeint les dunes en or.<br>Le premier souffle du jour t'appartient.</span>
      <span class="slogan-t sl-noon">Sous le soleil de feu, aucune ombre ne ment.<br>Le désert révèle tout ce que tu fuis.</span>
    </p>
  </div>
  <div class="hero-scroll-hint">
    <span>Descendre</span>
    <div class="scroll-line"></div>
  </div>
</section>

<!-- ══ L'APPEL ══ -->
<section id="appel">
  <div class="si">
    <span class="s-label reveal">· L'Appel ·</span>
    <h2 class="s-title reveal rd1">Le Concept</h2>
    <p class="s-intro reveal rd2">Une communauté qui ne ressemble à aucune autre.<br>Un chemin tracé dans le sable, effacé, retracé.</p>
    <div class="appel-grid">
      <div class="appel-text reveal">
        <p>Renait-Sens est né d'un constat simple : les humains ont besoin de silence pour entendre leur propre voix. Dans le désert du Tassili, chaque grain de sable est une décision que le vent a prise il y a des millénaires.</p>
        <blockquote class="appel-bq">« La tempête de sable aveugle, mais elle finit toujours par retomber. »</blockquote>
        <p>Nos 10 Modules sont des oasis. Chacun t'invite à déposer un poids, à respirer, à reprendre la marche avec plus de clarté. Ce n'est pas un programme. C'est une odyssée.</p>
      </div>
      <div class="appel-visual reveal rd2">
        <div class="appel-canvas-wrap">
          <canvas id="appel-canvas"></canvas>
        </div>
        <span class="appel-cap">Tassili n'Ajjer · Algérie</span>
      </div>
    </div>
    <div class="pillars">
      <div class="pillar reveal"><span class="pillar-icon">⏳</span><h3>L'Aman</h3><p>Un pacte de confidentialité absolue. Sous cette tente, on dépose les armes.</p></div>
      <div class="pillar reveal rd1"><span class="pillar-icon">🌵</span><h3>Les Modules</h3><p>Dix étapes de transformation. Du Reset à la Renaissance.</p></div>
      <div class="pillar reveal rd2"><span class="pillar-icon">🐪</span><h3>La Caravane</h3><p>Des Nomades qui avancent ensemble, sans juger le sac du voisin.</p></div>
    </div>
  </div>
</section>

<!-- ══ TRAVERSÉES ══ -->
<section id="traversees">
  <div class="si">
    <span class="s-label reveal">· Les Traversées ·</span>
    <h2 class="s-title reveal rd1">Trois Chemins, Un Désert</h2>
    <p class="s-intro reveal rd2">Quelle traversée résonne en toi ?</p>
    <div class="trav-grid">
      <div class="tcard reveal">
        <div class="tcard-bg bg-regard"></div><div class="tcard-ov"></div>
        <div class="tcard-body">
          <span class="tcard-num">Traversée · 01</span>
          <div class="tcard-name">Regard</div>
          <div class="tcard-sub">L'éveil du premier regard</div>
          <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Le sable révèle ce que tu refusais de voir. Un regard neuf, un horizon sans limites. »</p></div>
        </div>
      </div>
      <div class="tcard reveal rd1">
        <div class="tcard-bg bg-presence"></div><div class="tcard-ov"></div>
        <div class="tcard-body">
          <span class="tcard-num">Traversée · 02</span>
          <div class="tcard-name">Présence</div>
          <div class="tcard-sub">L'art d'être là, entier</div>
          <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Le désert ne demande rien, sinon ta présence absolue. Ici, le passé n'existe plus. »</p></div>
        </div>
      </div>
      <div class="tcard reveal rd2">
        <div class="tcard-bg bg-absolu"></div><div class="tcard-ov"></div>
        <div class="tcard-body">
          <span class="tcard-num">Traversée · 03</span>
          <div class="tcard-name">Absolu</div>
          <div class="tcard-sub">Djanet, l'ultime horizon</div>
          <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Sous les étoiles du Tassili, tu comprends enfin que tu as toujours été libre. »</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ TIMELINE ══ -->
<section id="timeline">
  <div class="si">
    <div class="tl-intro">
      <span class="s-label reveal">· L'Absolu · Djanet ·</span>
      <h2 class="s-title reveal rd1">7 Jours dans l'Infini</h2>
      <p class="s-intro reveal rd2">L'itinéraire de la Traversée Absolu. Chaque jour, un module. Chaque module, une métamorphose.</p>
    </div>
    <div class="tl-track">
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j1"></div></div><div class="sn"><div class="sn-c">J1</div></div><div class="st"><span class="s-jour">Jour Premier · Arrivée</span><div class="s-t">Le Grand Reset</div><p class="s-d">Arrivée à Djanet. Le silence du Tassili accueille chaque voyageur. On dépose les téléphones, les rôles, les masques.</p><div class="s-meta">⏳ « Éteins le bruit. Le désert commence ici. Module RESET. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j2"></div></div><div class="sn"><div class="sn-c">J2</div></div><div class="st"><span class="s-jour">Jour Deux · Aurore</span><div class="s-t">Le Reboot</div><p class="s-d">Lever avant l'aube. Marche vers les premières dunes. Le corps redémarre, la tête suit.</p><div class="s-meta">💫 « Comme le soleil sur le Tassili, tu te lèves neuf chaque matin. Module REBOOT. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j3"></div></div><div class="sn"><div class="sn-c">J3</div></div><div class="st"><span class="s-jour">Jour Trois · Erg</span><div class="s-t">La Clarté</div><p class="s-d">Traversée de l'Erg de Djanet. Le sable révèle sa géométrie. La clarté émerge du mouvement.</p><div class="s-meta">🌵 « Le désert nettoie ce que les mots ne peuvent pas nommer. Module CLARTÉ. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j4"></div></div><div class="sn"><div class="sn-c">J4</div></div><div class="st"><span class="s-jour">Jour Quatre · Grotte</span><div class="s-t">L'Ancrage</div><p class="s-d">Gravures rupestres du Tassili. Des humains ont tracé leur existence ici depuis 10 000 ans. Tu traces la tienne.</p><div class="s-meta">🐪 « Tu es plus ancien que tu ne le crois. Module ANCRAGE. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j5"></div></div><div class="sn"><div class="sn-c">J5</div></div><div class="st"><span class="s-jour">Jour Cinq · Bivouac</span><div class="s-t">Le Silence</div><p class="s-d">Nuit sous les étoiles. Pas de tente, juste le ciel. Le silence du Sahara est la chose la plus dense qui soit.</p><div class="s-meta">🌙 « Dans le silence, tu entends enfin ce que tu cherchais. Module SILENCE. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j6"></div></div><div class="sn"><div class="sn-c">J6</div></div><div class="st"><span class="s-jour">Jour Six · Sommet</span><div class="s-t">La Vision</div><p class="s-d">Ascension du plateau. Du sommet, l'horizon infini. Le passé et le futur deviennent la même ligne.</p><div class="s-meta">⭐️ « L'étoile polaire ne bouge pas. Trouve ton étoile. Module VISION. »</div></div></div>
      <div class="tl-step"><div class="sv"><div class="sv-in bg-j7"></div></div><div class="sn"><div class="sn-c">J7</div></div><div class="st"><span class="s-jour">Jour Sept · Renaissance</span><div class="s-t">La Renaissance</div><p class="s-d">Dernier lever de soleil sur Djanet. La caravane se sépare, mais chaque Nomade repart avec quelque chose d'indestructible.</p><div class="s-meta">🌞 « Le désert ne t'a pas changé. Il t'a révélé. Module RENAISSANCE. »</div></div></div>
    </div>
  </div>
</section>

<!-- ══ BULLE ══ -->
<section id="bulle">
  <div class="si">
    <span class="s-label reveal">· La Bulle ·</span>
    <h2 class="s-title reveal rd1">La Communauté</h2>
    <p class="s-intro reveal rd2">Un espace protégé où chaque voix compte.</p>
    <div class="bulle-grid">
      <div class="pacte-box reveal">
        <div class="pacte-tit">Le Pacte de l'Aman</div>
        <blockquote class="pacte-q">« Sous cette tente, on dépose les armes.<br>La confidentialité est notre loi.<br>Le non-jugement, notre air.<br>La bienveillance radicale, notre feu. »</blockquote>
        <span class="pacte-em" id="pacte-em">⏳</span>
      </div>
      <div class="mod-list reveal rd2">
        <div class="mod-item"><span class="m-num">01</span><span class="m-name">Reset — Éteindre le bruit</span></div>
        <div class="mod-item"><span class="m-num">02</span><span class="m-name">Reboot — Repartir de zéro</span></div>
        <div class="mod-item"><span class="m-num">03</span><span class="m-name">Clarté — Voir sans filtre</span></div>
        <div class="mod-item"><span class="m-num">04</span><span class="m-name">Ancrage — Retrouver ses racines</span></div>
        <div class="mod-item"><span class="m-num">05</span><span class="m-name">Silence — Entendre l'essentiel</span></div>
        <div class="mod-item"><span class="m-num">06—10</span><span class="m-name">Vision · Lâcher-prise · Connexion · Puissance · Renaissance</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ══ DJANET ══ -->
<section id="djanet">
  <canvas id="djanet-canvas"></canvas>
  <div class="djanet-c">
    <span class="dj-lbl">· L'Absolu ·</span>
    <h2 class="dj-title">Djanet<em>L'horizon qui n'existe pas encore</em></h2>
    <p class="dj-sub">Le désert n'a pas de fin...<br>Rejoins la caravane des Nomades de l'Éveil.</p>
    <div class="dj-btns">
      <a href="{{route("traverser")}}" class="btn-p">⭐️ &nbsp;Rejoindre la Caravane</a>
      <a href="{{route("traversees")}}" class="btn-s">🌵 &nbsp;Choisir ma Traversée</a>
    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<x-footer/>

<!-- ══ WIDGET ══ -->
<x-widget/>
@endsection

@push('scripts')
@vite(['resources/js/odysse_desert.js','resources/js/widget_AI.js'])
@endpush