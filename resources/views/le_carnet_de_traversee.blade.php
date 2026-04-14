@extends('layouts.app')

@section('title','Renait-Sens — Le Carnet de Traversée')

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

  <!-- ══ MOBILE NAV ══ -->
  <div class="mobile-nav" id="mobile-nav">
    <a href="#carnet-intro"  onclick="closeMenu()">Le Carnet</a>
    <div class="mnav-div"></div>
    <a href="#carnet-pages"  onclick="closeMenu()">Les 9 Pages</a>
    <div class="mnav-div"></div>
    <a href="#carnet-traversees" onclick="closeMenu()">Ma Traversée</a>
    <div class="mnav-div"></div>
    <a href="{{route('login')}}" style="font-size:1rem;letter-spacing:.2em;margin-top:1rem;color:var(--acc)">Accéder au Carnet</a>
  </div>

  <!-- ══ HERO — LE CARNET ══ -->
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
      <span class="h-eyebrow">Renait-Sens · Le Carnet de Traversée</span>
      <h1 class="h-title">
        Ton Premier Passage
        <em id="hero-sub">Pas un ebook. Un rite d'entrée.</em>
      </h1>
      <div class="h-div"><span></span><em id="hero-icon">🌙</em><span></span></div>
      <p class="h-slogan">
        <span class="slogan-t sl-night">Ici, tu n'as pas besoin de tout expliquer.<br>Tu as juste besoin d'être honnête avec ce que tu vis.</span>
        <span class="slogan-t sl-dawn">L'aube commence par nommer ce qu'on porte.<br>Le Carnet est ce premier souffle.</span>
        <span class="slogan-t sl-noon">Aucune clarté ne naît sans vérité.<br>Le Carnet te remet face aux bonnes questions.</span>
      </p>
      <div class="hero-cta-wrap">
        <a href="{{route('login')}}" class="btn-p">📖 &nbsp;Accéder au Carnet</a>
      </div>
    </div>
    <div class="hero-scroll-hint">
      <span>Descendre</span>
      <div class="scroll-line"></div>
    </div>
  </section>

  <!-- ══ INTRO — CE QU'EST LE CARNET ══ -->
  <section id="carnet-intro">
    <div class="si">
      <span class="s-label reveal">· Le Carnet de Traversée ·</span>
      <h2 class="s-title reveal rd1">Un Passage, Pas un Contenu</h2>
      <p class="s-intro reveal rd2">En 10 à 20 minutes, tu poses ce que tu portes.<br>Tu nommes ce que tu traverses. Tu touches ce qui appelle en toi.</p>
      <div class="appel-grid">
        <div class="appel-text reveal">
          <p>Le Carnet de Traversée n'est pas une bibliothèque. Ce n'est pas 40 ressources ou un programme en 12 étapes. C'est un espace construit comme un mini-rite de passage : accueillir, ralentir, faire nommer, faire voir, faire ressentir, ouvrir une suite.</p>
          <blockquote class="appel-bq">« Ici, tu n'as pas besoin de savoir déjà tout expliquer. Tu as juste besoin d'être honnête avec ce que tu vis. »</blockquote>
          <p>Chaque page est un pas — pas vers du contenu, vers toi-même. Le Carnet ne te donne pas de réponses. Il te remet face aux bonnes questions, celles que tu repousses depuis trop longtemps.</p>
        </div>
        <div class="appel-visual reveal rd2">
          <div class="appel-canvas-wrap">
            <canvas id="appel-canvas"></canvas>
          </div>
          <span class="appel-cap">Tassili n'Ajjer · Algérie</span>
        </div>
      </div>
      <div class="pillars">
        <div class="pillar reveal">
          <span class="pillar-icon">📖</span>
          <h3>Nommer</h3>
          <p>Rupture, fatigue, vide, transition. Mettre en mots ce qu'on traverse — c'est déjà commencer à en sortir.</p>
        </div>
        <div class="pillar reveal rd1">
          <span class="pillar-icon">🔥</span>
          <h3>Voir</h3>
          <p>Ce que tu ne veux plus nourrir. Ce que tu veux laisser mourir dans le sable. Ce qui appelle à renaître.</p>
        </div>
        <div class="pillar reveal rd2">
          <span class="pillar-icon">🧭</span>
          <h3>Ouvrir</h3>
          <p>Une suite naturelle. Pas de pression. Un passage vers l'étape suivante de ta traversée Renait-Sens.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ LES 9 PAGES ══ -->
  <section id="carnet-pages">
    <div class="si">
      <span class="s-label reveal">· Structure ·</span>
      <h2 class="s-title reveal rd1">Les 9 Pages du Carnet</h2>
      <p class="s-intro reveal rd2">Chaque page a un rôle précis. Rien de superflu.</p>

      <div class="tl-track" style="margin-top:4rem">

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j1"></div></div>
          <div class="sn"><div class="sn-c">01</div></div>
          <div class="st">
            <span class="s-jour">Page Première · Accueil</span>
            <div class="s-t">Bienvenue dans la Traversée</div>
            <p class="s-d">Un mot d'accueil sobre, humain. Pas un roman. Juste ce qu'il faut pour sentir : ici, ce n'est pas du contenu banal. Tu entres dans un espace à part.</p>
            <div class="s-meta">📖 « Tu n'as pas besoin de savoir déjà tout expliquer. »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j2"></div></div>
          <div class="sn"><div class="sn-c">02</div></div>
          <div class="st">
            <span class="s-jour">Page Deux · Ralentir</span>
            <div class="s-t">Audio d'ouverture</div>
            <p class="s-d">2 à 4 minutes. Une voix calme, un cadre simple. Pas de grand discours. L'objectif : accueillir, poser le cadre, ralentir. Te dire comment utiliser le Carnet.</p>
            <div class="s-meta">🎧 « Avant de lire, laisse le silence entrer. »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j3"></div></div>
          <div class="sn"><div class="sn-c">03</div></div>
          <div class="st">
            <span class="s-jour">Page Trois · Vérité</span>
            <div class="s-t">Où j'en suis aujourd'hui</div>
            <p class="s-d">Le cœur de l'entrée. Tu nommes ce que tu traverses, ce que tu ressens, ce que tu n'arrives plus à porter, ce que tu ne veux plus continuer à vivre comme avant.</p>
            <div class="s-meta">⏳ « Aujourd'hui, ce qui me fatigue le plus est… »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j4"></div></div>
          <div class="sn"><div class="sn-c">04</div></div>
          <div class="st">
            <span class="s-jour">Page Quatre · Sens</span>
            <div class="s-t">Ce que je traverse vraiment</div>
            <p class="s-d">Rupture, fatigue, perte de repères, vide intérieur, transition. On t'aide à mettre du sens — pas en mode psycho compliquée, en mode humain. Dans laquelle te reconnais-tu ?</p>
            <div class="s-meta">🌵 « Simplifier ton chaos. C'est déjà un premier pas. »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j5"></div></div>
          <div class="sn"><div class="sn-c">05</div></div>
          <div class="st">
            <span class="s-jour">Page Cinq · Lâcher</span>
            <div class="s-t">Ce que je ne veux plus continuer</div>
            <p class="s-d">Ce que tu ne peux plus nier. Ce que tu repousses depuis trop longtemps. Cette page t'oblige à entrer dans le vrai — sans détour, sans filtre.</p>
            <div class="s-meta">🌙 « Ce que je ne peux plus nier, c'est… »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j6"></div></div>
          <div class="sn"><div class="sn-c">06</div></div>
          <div class="st">
            <span class="s-jour">Page Six · Élan</span>
            <div class="s-t">Ce qui appelle en moi</div>
            <p class="s-d">On passe de la souffrance à l'élan. Ce que tu veux retrouver, ce que tu veux laisser mourir, la personne que tu as envie de redevenir. C'est ici que tout bascule.</p>
            <div class="s-meta">⭐️ « La personne que j'ai envie de redevenir… »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j7"></div></div>
          <div class="sn"><div class="sn-c">07</div></div>
          <div class="st">
            <span class="s-jour">Page Sept · Exercice</span>
            <div class="s-t">La Page de Vérité</div>
            <p class="s-d">Un seul exercice, mais puissant. Trois phrases à compléter. Ce que je vis aujourd'hui, c'est… Ce que je ne peux plus nier, c'est… Le premier pas juste pour moi, c'est…</p>
            <div class="s-meta">🔥 « Simple. Fort. Utilisable. Maintenant. »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j2"></div></div>
          <div class="sn"><div class="sn-c">08</div></div>
          <div class="st">
            <span class="s-jour">Page Huit · Ancrage</span>
            <div class="s-t">Audio de Recentrage</div>
            <p class="s-d">3 à 7 minutes. Respiration, retour à soi, ancrage. Très accessible — pas un rituel perché. L'objectif : faire vivre une expérience, pas seulement lire un texte.</p>
            <div class="s-meta">🎧 « Avant de continuer, reviens à toi. »</div>
          </div>
        </div>

        <div class="tl-step">
          <div class="sv"><div class="sv-in bg-j7"></div></div>
          <div class="sn"><div class="sn-c">09</div></div>
          <div class="st">
            <span class="s-jour">Page Neuf · Bascule</span>
            <div class="s-t">Et maintenant ?</div>
            <p class="s-d">La page de transition. Tu passes de "je comprends mieux" à "je suis prêt à aller plus loin". Trois options claires : relire seul, réserver un Diagnostic Sahara, ou découvrir la suite.</p>
            <div class="s-meta">🌞 « Pas une vente. Une suite naturelle, logique. »</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ══ TRAVERSÉES — QUELLE SUITE ? ══ -->
  <section id="carnet-traversees">
    <div class="si">
      <span class="s-label reveal">· La Suite ·</span>
      <h2 class="s-title reveal rd1">Quelle Traversée t'appelle ?</h2>
      <p class="s-intro reveal rd2">Le Carnet est l'entrée. Les Traversées, c'est la route.</p>
      <div class="trav-grid">
        <div class="tcard reveal">
          <a href="{{ route('traverser', ['choix' => 'regard']) }}" class="tcard-link">
          <div class="tcard-bg bg-regard"></div><div class="tcard-ov"></div>
          <div class="tcard-body">
            <span class="tcard-num">Traversée · 01</span>
            <div class="tcard-name">Regard</div>
            <div class="tcard-sub">L'éveil du premier regard</div>
            <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Le sable révèle ce que tu refusais de voir. Un regard neuf, un horizon sans limites. »</p></div>
          </div>
          </a>
        </div>
        <div class="tcard reveal rd1">
          <a href="{{ route('traverser', ['choix' => 'presence']) }}" class="tcard-link">
          <div class="tcard-bg bg-presence"></div><div class="tcard-ov"></div>
          <div class="tcard-body">
            <span class="tcard-num">Traversée · 02</span>
            <div class="tcard-name">Présence</div>
            <div class="tcard-sub">L'art d'être là, entier</div>
            <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Le sahara ne demande rien, sinon ta présence absolue. Ici, le passé n'existe plus. »</p></div>
          </div>
          </a>
        </div>
        <div class="tcard reveal rd2">
          <a href="{{ route('traverser', ['choix' => 'absolu']) }}" class="tcard-link">
          <div class="tcard-bg bg-absolu"></div><div class="tcard-ov"></div>
          <div class="tcard-body">
            <span class="tcard-num">Traversée · 03</span>
            <div class="tcard-name">Absolu</div>
            <div class="tcard-sub">Djanet, l'ultime horizon</div>
            <div class="tcard-prov"><span class="prov-lbl">Provision Renaissance</span><p>« Sous les étoiles du Tassili, tu comprends enfin que tu as toujours été libre. »</p></div>
          </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ CTA FINAL ══ -->
  <section id="djanet">
    <canvas id="djanet-canvas"></canvas>
    <div class="djanet-c">
      <span class="dj-lbl">· Le Carnet de Traversée ·</span>
      <h2 class="dj-title">Entre dans le vrai<em>Le premier pas commence ici</em></h2>
      <p class="dj-sub">10 à 20 minutes.<br>Honnêteté. Clarté. Un premier pas juste.</p>
      <div class="dj-btns">
        <a href="{{route('login')}}" class="btn-p">📖 &nbsp;Accéder au Carnet</a>
        <a href="{{route('traversees')}}" class="btn-s">🌵 &nbsp;Choisir ma Traversée</a>
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