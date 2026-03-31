@extends('layouts.app')

@push("styles")
    @vite(['resources/css/traversees.css','resources/css/widget_AI.css'])
@endpush

@section('content')

<div class="cur" id="cur"></div>
<div class="cur-r" id="cur-r"></div>
<div class="grain"></div>
<canvas id="bg-canvas"></canvas>

<!-- MODE SWITCHER -->
<div class="msw-wrap">
  <button class="msw on" data-m="night" onclick="setMode('night')">🌙 Nuit</button>
  <button class="msw"    data-m="dawn"  onclick="setMode('dawn')">🌅 Aube</button>
  <button class="msw"    data-m="noon"  onclick="setMode('noon')">🌞 Midi</button>
</div>

<!-- NAVBAR -->

  <x-header/>
<!-- MOBILE NAV -->
<div class="mob-nav" id="mob-nav">
  <a href="/" onclick="closeMenu()">L'Appel</a>
  <a href="#traversees" onclick="closeMenu()">Les Traversées</a>
  <a href="/" onclick="closeMenu()">La Bulle</a>
  <a href="/" onclick="closeMenu()">Djanet</a>
</div>

<!-- ══ HERO ══ -->
<section id="hero">
  <div class="hero-sky"></div>
  <div class="hero-overlay"></div>
  <canvas id="hero-canvas"></canvas>

  <div class="hero-dunes">
    <svg viewBox="0 0 1440 280" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="hdu1" x1="0" y1="0" x2="0" y2="1">
          <stop id="hdu1a" offset="0%" stop-color="#1A1208" stop-opacity=".75"/>
          <stop id="hdu1b" offset="100%" stop-color="#07071A"/>
        </linearGradient>
        <linearGradient id="hdu2" x1="0" y1="0" x2="0" y2="1">
          <stop id="hdu2a" offset="0%" stop-color="#241A0A" stop-opacity=".9"/>
          <stop id="hdu2b" offset="100%" stop-color="#07071A"/>
        </linearGradient>
      </defs>
      <path d="M0,175 Q200,95 400,145 Q600,195 800,115 Q1000,38 1200,108 Q1362,158 1440,128 L1440,280 L0,280Z" fill="url(#hdu1)" opacity=".52"/>
      <path d="M0,205 Q180,140 360,182 Q540,228 720,160 Q900,96 1100,162 Q1300,218 1440,182 L1440,280 L0,280Z" fill="url(#hdu2)" opacity=".88"/>
      <path id="hcrest" d="M0,207 Q180,142 360,184 Q540,230 720,162 Q900,98 1100,164 Q1300,220 1440,184" fill="none" stroke="#C9A96E" stroke-width=".6" opacity=".28"/>
    </svg>
  </div>

  <div class="hero-content">
    <span class="h-eyebrow">Renait-Sens · Propositions</span>
    <h1 class="h-title">
      Les Traversées
      <em>Trois chemins. Un seul sahara.</em>
    </h1>
    <div class="h-div"><span></span><em id="hero-icon">🌙</em><span></span></div>
    <p class="h-sub">Ce ne sont pas des offres. Ce sont des odyssées.<br>Choisis celle qui résonne dans tes dunes intérieures.</p>
  </div>
  <div class="hero-scroll"><span>Descendre</span><div class="scroll-line"></div></div>
</section>

<!-- ══ TRAVERSÉES ══ -->
<section class="section" id="traversees">
  <div class="si">
    <span class="s-label reveal">· Les Traversées ·</span>
    <h2 class="s-title reveal rd1">Trois Chemins, Une Transformation</h2>
    <p class="s-intro reveal rd2">L'Offrande n'est pas un prix. C'est un investissement dans ta renaissance.</p>

    <div class="traversees-wrap">

      <!-- ═ REGARD ═ -->
      <div class="tcard reveal">
        <div class="tcard-visual">
          <div class="tcard-bg bg-regard"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 01</span>
        </div>
        <div class="tcard-body">
          <div class="tcard-name">Regard</div>
          <div class="tcard-tagline">Se poser, cesser de fuir et enfin se voir.</div>

          <div class="tcard-price">
            <span class="price-label">L'Offrande</span>
            <span class="price-val">800 €</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois pour ralentir le rythme</div>

          <div class="tcard-sep"></div>

          <div class="tcard-feats">
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">3 Dialogues à cœur ouvert</div>
                <div class="feat-desc">Une rencontre mensuelle avec la Sentinelle pour identifier l'essentiel.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Le Lien</div>
                <div class="feat-desc">Un fil direct avec la Sentinelle pour tes moments de doute ou de révélation.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Carnet de la traversée </div>
                <div class="feat-desc">Un écrin pour tes pensées, pour fixer tes prises de conscience.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Une méditation par module </div>
                <div class="feat-desc"></div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Rythme quotidien des nomades (exercice du jour)</div>
                <div class="feat-desc"></div>
              </div>
            </div>
          </div>

          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Passer de l'aveuglement à la clarté.</div>
          </div>

          <a href="{{ route('traverser', ['choix' => 'regard']) }}" class="tcard-cta outline">Commencer ce chemin →</a>
        </div>
      </div>

      <!-- ═ PRÉSENCE ═ -->
      <div class="tcard featured reveal rd1">
        <div class="badge">Le plus choisi</div>
        <div class="tcard-visual">
          <div class="tcard-bg bg-presence"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 02</span>
        </div>
        <div class="tcard-body">
          <div class="tcard-name">Présence</div>
          <div class="tcard-tagline">Habiter son corps, ses sens et chaque instant de sa vie.</div>

          <div class="tcard-price">
            <span class="price-label">L'Offrande</span>
            <span class="price-val">1 400 €</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois pour s'ancrer</div>

          <div class="tcard-sep"></div>

          <div class="tcard-feats">
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">6 Dialogues profonds</div>
                <div class="feat-desc">Deux rendez-vous par mois avec la Sentinelle pour déconstruire et rebâtir.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">L'Oasis</div>
                <div class="feat-desc">L'accès à un espace de partage intime avec d'autres Nomades.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">EFT</div>
                <div class="feat-desc">Des rituels sensoriels à pratiquer chez soi pour reconnecter l'esprit au corps.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Atelier en live avec la sentinelle</div>
                <div class="feat-desc">  </div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Une méditation par module</div>
                <div class="feat-desc">  </div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Rythme quotidien des nomades (exercice du jour)</div>
                <div class="feat-desc">  </div>
              </div>
            </div>
          </div>

          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Ne plus subir, mais habiter pleinement sa propre existence.</div>
          </div>

          <a href="{{ route('traverser', ['choix' => 'presence']) }}" class="tcard-cta primary">Choisir la Présence →</a>
        </div>
      </div>

      <!-- ═ ABSOLU ═ -->
      <div class="tcard reveal rd2">
        <div class="tcard-visual">
          <div class="tcard-bg bg-absolu"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 03</span>
        </div>
        <div class="tcard-body">
          <div class="tcard-name">Absolu</div>
          <div class="tcard-tagline">Le dépouillement total pour la renaissance ultime.</div>

          <div class="tcard-price" style="flex-direction:column;align-items:flex-start;gap:.2rem">
            <span class="price-label">L'Offrande</span>
            <span class="price-demand">Prix sur demande</span>
            <span style="font-size:.78rem;font-style:italic;color:var(--txt4)">L'engagement d'une vie</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois de préparation + 7 jours d'immersion</div>

          <div class="tcard-sep"></div>

          <div class="tcard-feats">
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">L'Appel du SAHARA</div>
                <div class="feat-desc">Une semaine à Djanet, entre roche rouge et sable d'or, Sahara algérien.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Le Silence</div>
                <div class="feat-desc">Apprendre à écouter ce que le vide a à nous dire.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">La Mue</div>
                <div class="feat-desc">Un accompagnement quotidien dans les dunes par les Sentinelles, sans artifice, pour laisser mourir l'ancien et naître le nouveau.</div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Accès aux 2 OASIS</div>
                <div class="feat-desc">
                    L'accès à un espace de partage intime avec d'autres Nomades <br>
                    L'accès privée entre nomades SAHARIEN <br>
                   
                </div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Une méditaion par module </div>
                <div class="feat-desc"></div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Atelier en live avec la sentinelle</div>
                <div class="feat-desc"></div>
              </div>
            </div>
            <div class="feat">
              <div class="feat-dot"></div>
              <div class="feat-content">
                <div class="feat-title">Rythme quotidien des nomades (exercice du jour)</div>
                <div class="feat-desc"></div>
              </div>
            </div>
          </div>
          </div>
           

          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Vivre l'expérience du sacré pour revenir transformé à jamais.</div>
          </div>

          <a href="{{ route('traverser', ['choix' => 'absolu']) }}" class="tcard-cta outline">Soumettre mon Appel →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══ POURQUOI ══ -->
<section class="section alt" id="pourquoi">
  <div class="si">
    <span class="s-label reveal">· Pourquoi ces mots ·</span>
    <h2 class="s-title reveal rd1">Le Langage du Désert</h2>

    <div class="why-grid">
      <div class="why-text reveal-left">
        <p>Chaque mot a été choisi pour créer une rupture avec le vocabulaire du commerce ordinaire. Ici, on ne vend pas des services. On invite à une traversée.</p>
        <blockquote>« L'Offrande remplace le tarif. On n'investit pas dans une prestation — on investit dans sa propre renaissance. »</blockquote>
        <p>Regard, Présence, Absolu : trois mots courts, denses, qui dessinent une ascension. Du premier regard sur soi à la dissolution totale de l'ancien.</p>
      </div>

      <div class="why-pillars reveal-right">
        <div class="why-pillar">
          <div class="wp-icon">👁</div>
          <div>
            <div class="wp-title">Regard · Présence · Absolu</div>
            <div class="wp-desc">Une ascension spirituelle en trois actes. Court, dense, inévitable.</div>
          </div>
        </div>
        <div class="why-pillar">
          <div class="wp-icon">⏳</div>
          <div>
            <div class="wp-title">L'Offrande</div>
            <div class="wp-desc">Remplacer "Tarif" change la relation. On ne paye pas une facture, on investit dans une transformation.</div>
          </div>
        </div>
        <div class="why-pillar">
          <div class="wp-icon">🐪</div>
          <div>
            <div class="wp-title">Les Nomades</div>
            <div class="wp-desc">Une fraternité immédiate. On n'est plus un client, on est un voyageur en quête de sens.</div>
          </div>
        </div>
        <div class="why-pillar">
          <div class="wp-icon">🌵</div>
          <div>
            <div class="wp-title">La Sentinelle</div>
            <div class="wp-desc">Pas un coach, pas un thérapeute — un gardien du seuil qui accompagne la traversée.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ CTA FINAL ══ -->
<section id="cta-final">
  <canvas id="cta-canvas"></canvas>
  <div class="cta-content">
    <span class="cta-label">· L'Appel ·</span>
    <h2 class="cta-title reveal">
      Quelle Traversée<br>t'appelle ?
      <em>Le sahara n'a pas de fin...</em>
    </h2>
    <p class="cta-sub reveal rd1">Ne choisis pas avec ta tête. Écoute le grain de sable qui frémit sous tes pieds.</p>
    <div class="cta-btns reveal rd2">
      <a href="{{route("traversees")}}" class="btn-p">⭐ Voir les Traversées</a>
      <a href="{{route("traverser")}}"   class="btn-s">🌵 Rejoindre la Caravane</a>
    </div>
  </div>
</section>

<x-footer/>

<x-widget/>
@endsection
@push("scripts")
    @vite(['resources/js/traversees.js','resources/js/widget_AI.js'])
@endpush