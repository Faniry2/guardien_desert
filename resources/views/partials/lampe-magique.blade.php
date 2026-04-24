{{-- Canvas WebGL (plein écran, z-index 1) --}}
<canvas id="lmp-canvas"></canvas>

{{-- Lampe + hint --}}
<div id="lmp-scene">
    {{-- Bouton épingler/réduire vers sidebar (visible au hover) --}}
    <button id="lmp-pin-btn"
        onclick="lmpTogglePin()"
        title="Épingler à la sidebar">
        
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="17" x2="12" y2="22"/>
            <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"/>
        </svg>
        <span id="lmp-pin-label">Épingler</span>
    </button>
    <button id="lmp-img-btn"
        onclick="lmpClick()"
        title="Frotte la lampe"
        style="background:none;border:none;padding:0;cursor:pointer;display:block;pointer-events:all;">
        <img id="lmp-img"
        onclick="lmpClick()"
         src="{{ asset('images/lamp_gauche.png') }}"
         alt="Lampe magique">
    </button>
    {{-- <img id="lmp-img"
         src="{{ asset('images/lamp.png') }}"         
         alt="Lampe magique"
         onclick="lmpClick()"
         title="Frotte la lampe"> --}}
    <div id="lmp-hint">&#10022; Frotte la lampe</div>
</div>

{{-- Modal génie --}}
<div class="lmp-modal-bg" id="lmp-modal-bg" onclick="lmpCloseBg(event)">
    <div class="lmp-modal-box">
        <div class="lmp-modal-sparks" id="lmp-modal-sparks"></div>
 
        {{-- Header --}}
        <div class="lmp-modal-header">
            <span style="font-size:2.4rem;display:block;margin-bottom:.8rem">
                <img src="{{ asset('images/carnet_intime.png') }}" alt="Génie" style="width:60px;display:inline-block;vertical-align:middle">
            </span>
            <h2 class="lmp-modal-title">Le Carnet des Nomades</h2>
            <p class="lmp-modal-sub">Exploration Int&eacute;rieure</p>
            <div class="lmp-modal-sep"><div class="lmp-modal-gem"></div></div>

            {{-- ← Bouton X fermer (mobile uniquement) --}}
            <button onclick="lmpClose()"
                    id="lmp-close-btn"
                    aria-label="Fermer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
 
        {{-- Corps scrollable — FAQ accordion --}}
        <div class="lmp-modal-body">
 
            <p class="lmp-faq-section-title">&#10022; &nbsp; Questions du d&eacute;sert &nbsp; &#10022;</p>
 
            {{-- Q1 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Pourquoi ce carnet existe-t-il vraiment&nbsp;?
                </div>
                <div class="lmp-faq-a open">
                    Ce carnet est une porte. Une invitation &agrave; descendre en toi, au-del&agrave; des mots habituels, au-del&agrave; des r&eacute;ponses toutes faites. Il ne s&apos;agit pas seulement d&apos;&eacute;crire, mais de te rencontrer.
                </div>
            </div>
 
            {{-- Q2 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Que puis-je venir y d&eacute;poser&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Tout ce qui vit en toi. Tes &eacute;lans, tes peurs, tes doutes, tes silences. Ce que tu comprends&hellip; et ce que tu ne comprends pas encore. Ce carnet accueille autant l&apos;ombre que la lumi&egrave;re.
                </div>
            </div>
 
            {{-- Q3 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Et si je ne sais pas par o&ugrave; commencer&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Commence l&agrave; o&ugrave; c&apos;est vivant. Tu peux t&apos;ouvrir avec&nbsp;:
                    <ul>
                        <li>Qu&apos;est-ce qui est pr&eacute;sent en moi, ici et maintenant&nbsp;?</li>
                        <li>Qu&apos;est-ce que j&apos;&eacute;vite de regarder&nbsp;?</li>
                        <li>De quoi ai-je profond&eacute;ment besoin aujourd&apos;hui&nbsp;?</li>
                        <li>Qu&apos;est-ce que mon c&oelig;ur essaie de me dire&nbsp;?</li>
                    </ul>
                    <span style="display:block;margin-top:.6rem">Laisse ensuite les mots te guider.</span>
                </div>
            </div>
 
            {{-- Q4 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Comment &eacute;crire en conscience&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Ralentis. Respire. Ne cherche pas &agrave; &eacute;crire &laquo;&nbsp;quelque chose de beau&nbsp;&raquo; ou de coh&eacute;rent. &Eacute;cris vrai. M&ecirc;me si c&apos;est confus, brut ou inconfortable. C&apos;est souvent l&agrave; que r&eacute;side l&apos;essentiel.
                </div>
            </div>
 
            {{-- Q5 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Puis-je me laisser traverser sans comprendre&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Oui. Tout n&apos;a pas besoin d&apos;&ecirc;tre compris imm&eacute;diatement. Certaines prises de conscience &eacute;mergent plus tard. Ton r&ocirc;le n&apos;est pas de contr&ocirc;ler, mais d&apos;accueillir.
                </div>
            </div>
 
            {{-- Q6 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Que faire lorsque des &eacute;motions fortes apparaissent&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Ne les fuis pas. Laisse-les exister &agrave; travers tes mots. &Eacute;crire peut devenir un espace de lib&eacute;ration, un endroit o&ugrave; l&apos;&eacute;motion se transforme et se d&eacute;pose en douceur.
                </div>
            </div>
 
            {{-- Q7 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Ce carnet peut-il me transformer&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Oui, s&apos;il est utilis&eacute; avec pr&eacute;sence et sinc&eacute;rit&eacute;. Il devient alors un miroir, un guide silencieux, un t&eacute;moin de ton &eacute;volution.
                </div>
            </div>
 
            {{-- Q8 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Suis-je oblig&eacute; de partager&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Non. Ce carnet est sacr&eacute;. Le partage est une possibilit&eacute;, jamais une attente. Ce que tu gardes est aussi pr&eacute;cieux que ce que tu offres.
                </div>
            </div>
 
            {{-- Q9 --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Comment savoir si je suis &laquo;&nbsp;sur le bon chemin&nbsp;&raquo;&nbsp;?
                </div>
                <div class="lmp-faq-a">
                    Quand tu es honn&ecirc;te avec toi-m&ecirc;me. Quand tu oses &eacute;crire ce que tu n&apos;oses pas toujours dire. Quand tu te rapproches, pas &agrave; pas, de ce qui est vrai pour toi.
                </div>
            </div>
 
            <div class="lmp-faq-sep"></div>
 
            {{-- Portes d'exploration --}}
            <div class="lmp-faq-item">
                <div class="lmp-faq-q" onclick="lmpFaqToggle(this)">
                    Quelques portes d&apos;exploration
                </div>
                <div class="lmp-faq-a">
                    <ul>
                        <li>Qu&apos;est-ce qui me fait me sentir vivant(e)&nbsp;?</li>
                        <li>O&ugrave; est-ce que je me mens &agrave; moi-m&ecirc;me&nbsp;?</li>
                        <li>Qu&apos;est-ce que je suis pr&ecirc;t(e) &agrave; laisser derri&egrave;re moi&nbsp;?</li>
                        <li>Quelle version de moi cherche &agrave; &eacute;merger&nbsp;?</li>
                        <li>De quoi ai-je besoin pour me sentir align&eacute;(e)&nbsp;?</li>
                        <li>Qu&apos;est-ce que je retiens encore&hellip; et pourquoi&nbsp;?</li>
                    </ul>
                </div>
            </div>
 
            <div class="lmp-faq-sep"></div>
 
            {{-- Souffle final --}}
            <p class="lmp-faq-outro">
                Ce carnet n&apos;attend rien de toi.<br>
                Il ne juge pas, il ne corrige pas, il n&apos;impose rien.<br><br>
                Il est simplement l&agrave;, comme une pr&eacute;sence.<br>
                Un espace pour te d&eacute;poser, te r&eacute;v&eacute;ler,<br>
                et peut-&ecirc;tre&hellip; te retrouver.
            </p>
 
        </div>{{-- fin .lmp-modal-body --}}
 
        {{-- Footer boutons --}}
        <div class="lmp-modal-footer">
            @if(auth()->check() && auth()->user()->carnet)
                <a href="{{ route('carnet.day', auth()->user()->carnet->currentDayNumber() ?? 1) }}"
                class="lmp-btn lmp-btn-or">&#128214;&nbsp; Ouvrir le carnet</a>
            @endif
            @if(auth()->check() && auth()->user()->carnet)
            <a href="{{ route('detente.musique') }}"
               class="lmp-btn lmp-btn-or">&#127925;&nbsp; Musique</a>
            @endif
            <button class="lmp-btn lmp-btn-cl" onclick="lmpClose()">Fermer</button>
        </div>
 
    </div>
</div>