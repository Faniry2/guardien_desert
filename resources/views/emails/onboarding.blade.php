<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Onboarding Renaît-Sens</title>
<style>
  body{margin:0;padding:0;background:#03020A;font-family:'Georgia',serif;color:#F5E6C8;}
  .wrap{max-width:580px;margin:0 auto;background:#07060F;border:1px solid rgba(201,169,110,.15);}
  .header{padding:2.5rem;text-align:center;border-bottom:1px solid rgba(201,169,110,.12);}
  .logo{font-family:Georgia,serif;font-size:1.1rem;letter-spacing:.35em;color:#C9A96E;text-transform:uppercase;}
  .sep-line{width:40px;height:1px;background:linear-gradient(90deg,transparent,#C9A96E,transparent);margin:1rem auto;}
  .content{padding:2.5rem;}
  h1{font-family:Georgia,serif;font-size:1.5rem;font-weight:400;color:#F5E6C8;margin-bottom:1.2rem;line-height:1.3;}
  p{font-size:1rem;line-height:1.85;color:rgba(245,230,200,.78);margin-bottom:1.2rem;}
  .step-block{display:flex;gap:1.2rem;align-items:flex-start;
    padding:1.2rem;border-left:2px solid rgba(201,169,110,.3);
    margin-bottom:1rem;background:rgba(3,2,10,.5);}
  .step-num{font-family:Georgia,serif;font-size:1.4rem;color:#C9A96E;flex-shrink:0;min-width:28px;}
  .step-title{font-size:.85rem;letter-spacing:.18em;text-transform:uppercase;
    color:#F5E6C8;margin-bottom:.3rem;}
  .step-desc{font-size:.9rem;font-style:italic;color:rgba(245,230,200,.65);line-height:1.6;}
  .cta-btn{display:block;text-align:center;padding:1rem 2rem;margin:1.5rem 0;
    background:rgba(201,169,110,.12);border:1px solid rgba(201,169,110,.4);
    color:#C9A96E;text-decoration:none;font-size:.85rem;letter-spacing:.25em;text-transform:uppercase;}
  .footer{padding:1.5rem 2.5rem;text-align:center;border-top:1px solid rgba(201,169,110,.08);}
  .footer p{font-size:.78rem;color:rgba(245,230,200,.25);margin:0;line-height:1.6;}
  blockquote{border-left:2px solid #C9A96E;padding-left:1.2rem;margin:1.5rem 0;
    font-style:italic;font-size:1.1rem;color:rgba(201,169,110,.85);}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="logo">Renaît-Sens</div>
    <div class="sep-line"></div>
    <div style="font-size:.65rem;letter-spacing:.45em;color:rgba(201,169,110,.65);text-transform:uppercase;">
      Tes prochaines étapes
    </div>
  </div>

  <div class="content">
    <h1>
      {{ $user->prenom }}, la traversée commence maintenant. 🌵
    </h1>

    <blockquote>
      « Le sahara n'a pas de fin...<br>
      Il commence là où tu poses ton regard. »
    </blockquote>

    <p>
      Tu fais maintenant partie de la caravane. Voici ce qui t'attend dans les prochaines heures et les prochains jours.
    </p>

    <div class="step-block">
      <div class="step-num">01</div>
      <div>
        <div class="step-title">Accède au Carnet de Traversée</div>
        <div class="step-desc">
          Ton premier outil. 9 pages pour nommer ce que tu traverses,
          ralentir et préparer ta transformation. Commence dès aujourd'hui.
        </div>
      </div>
    </div>

    <div class="step-block">
      <div class="step-num">02</div>
      <div>
        <div class="step-title">Réserve ton premier Dialogue</div>
        <div class="step-desc">
          Ta Sentinelle t'attend. Clique sur le lien Calendly ci-dessous
          pour choisir ton créneau de premier rendez-vous.
        </div>
      </div>
    </div>

    <div class="step-block">
      <div class="step-num">03</div>
      <div>
        <div class="step-title">Rejoins l'Oasis</div>
        <div class="step-desc">
          L'espace de partage des Nomades. Un endroit protégé, confidentiel,
          où tu rencontres d'autres voyageurs en chemin.
        </div>
      </div>
    </div>

    @if($user->traversee === 'absolu')
    <div class="step-block" style="border-left-color:rgba(255,215,0,.5);">
      <div class="step-num" style="color:#FFD700;">04</div>
      <div>
        <div class="step-title" style="color:#FFD700;">Contrat Premium</div>
        <div class="step-desc">
          Ton contrat d'engagement Absolu te sera envoyé séparément dans 24h.
          Il détaille l'échéancier et les conditions de ton immersion au Sahara.
        </div>
      </div>
    </div>
    
    @endif
    {{-- Bloc identifiants de connexion --}}
    <div class="step-block" style="border-left-color:rgba(201,169,110,.6);margin-top:1.5rem;">
      <div class="step-num">🔑</div>
      <div>
        <div class="step-title">Tes identifiants de connexion</div>
        <div class="step-desc">
          Email : <strong style="color:#F5E6C8;">{{ $user->email }}</strong><br>
          Mot de passe temporaire : <strong style="color:#C9A96E;font-size:1.1rem;">{{ $tempPassword ?? '—' }}</strong><br><br>
          <span style="color:rgba(245,230,200,.5);">
            Connecte-toi et change ton mot de passe dès ta première connexion.
          </span>
        </div>
      </div>
    </div>

    <a href="https://renait-sens.com/carnet" class="cta-btn">
      📖 Accéder au Carnet de Traversée
    </a>

    {{-- <a href="https://calendly.com/renait-sens" class="cta-btn" style="margin-top:.5rem;">
      📅 Réserver mon premier Dialogue
    </a> --}}

    <p style="margin-top:2rem;">
      Si tu as la moindre question, réponds directement à cet email.
      Ta Sentinelle est là. Le désert n'est jamais traversé seul.
    </p>

    <p style="color:#C9A96E;font-style:italic;">
      — L'équipe Renaît-Sens
    </p>
  </div>
{{-- 
  <div class="footer">
    <p>
      Renaît-Sens · Tassili n'Ajjer · Algérie<br>
      Le sahara ne t'a pas changé — il t'a révélé.<br><br>
      <a href="#" style="color:rgba(201,169,110,.4);font-size:.72rem;">Se désinscrire</a>
    </p>
  </div> --}}
</div>
</body>
</html>
