<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Confirmation Renaît-Sens</title>
<style>
  body{margin:0;padding:0;background:#03020A;font-family:'Georgia',serif;color:#F5E6C8;}
  .wrap{max-width:580px;margin:0 auto;background:#07060F;border:1px solid rgba(201,169,110,.15);}
  .header{padding:2.5rem;text-align:center;border-bottom:1px solid rgba(201,169,110,.12);
    background:linear-gradient(180deg,#03020A,#07060F);}
  .logo{font-family:Georgia,serif;font-size:1.1rem;letter-spacing:.35em;
    color:#C9A96E;text-transform:uppercase;}
  .sep-line{width:40px;height:1px;background:linear-gradient(90deg,transparent,#C9A96E,transparent);
    margin:1rem auto;}
  .content{padding:2.5rem;}
  h1{font-family:Georgia,serif;font-size:1.6rem;font-weight:400;
    color:#F5E6C8;margin-bottom:.5rem;line-height:1.2;}
  h1 em{display:block;font-size:.55em;color:#C9A96E;letter-spacing:.12em;margin-top:.4rem;
    font-style:italic;}
  p{font-size:1rem;line-height:1.85;color:rgba(245,230,200,.78);margin-bottom:1.2rem;}
  .recap-box{background:rgba(3,2,10,.7);border:1px solid rgba(201,169,110,.2);
    padding:1.5rem;margin:2rem 0;}
  .recap-row{display:flex;justify-content:space-between;align-items:center;
    padding:.5rem 0;border-bottom:1px solid rgba(201,169,110,.08);}
  .recap-row:last-child{border-bottom:none;}
  .recap-label{font-size:.8rem;letter-spacing:.2em;color:rgba(245,230,200,.55);text-transform:uppercase;}
  .recap-val{font-size:.95rem;color:#F5E6C8;}
  .prix{font-size:1.3rem;color:#C9A96E;}
  .facture-note{font-size:.82rem;font-style:italic;color:rgba(245,230,200,.45);
    text-align:center;margin:1rem 0;}
  .cta-btn{display:block;text-align:center;padding:1rem 2rem;margin:1.5rem 0;
    background:rgba(201,169,110,.12);border:1px solid rgba(201,169,110,.4);
    color:#C9A96E;text-decoration:none;font-size:.85rem;letter-spacing:.25em;text-transform:uppercase;}
  .footer{padding:1.5rem 2.5rem;text-align:center;
    border-top:1px solid rgba(201,169,110,.08);}
  .footer p{font-size:.78rem;color:rgba(245,230,200,.25);margin:0;line-height:1.6;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="logo">Renaît-Sens</div>
    <div class="sep-line"></div>
    <div style="font-size:.65rem;letter-spacing:.45em;color:rgba(201,169,110,.65);text-transform:uppercase;">
      Confirmation de paiement
    </div>
  </div>

  <div class="content">
    <h1>
      Ta place est sécurisée, {{ $inscription->prenom }}.
      <em>Bienvenue dans la traversée.</em>
    </h1>

    <p>
      Ton paiement a bien été reçu. Tu fais maintenant partie de la caravane des Nomades de Renaît-Sens.
      Ce message vaut confirmation d'inscription.
    </p>

    <div class="recap-box">
      <div class="recap-row">
        <span class="recap-label">Nomade</span>
        <span class="recap-val">{{ $inscription->nomComplet }}</span>
      </div>
      <div class="recap-row">
        <span class="recap-label">Traversée</span>
        <span class="recap-val">{{ $inscription->libelleTraversee }}</span>
      </div>
      <div class="recap-row">
        <span class="recap-label">Montant réglé</span>
        <span class="recap-val prix">{{ $inscription->montant }} €</span>
      </div>
      <div class="recap-row">
        <span class="recap-label">Date</span>
        <span class="recap-val">{{ $inscription->paid_at?->format('d/m/Y') }}</span>
      </div>
      <div class="recap-row">
        <span class="recap-label">Réf. paiement</span>
        <span class="recap-val" style="font-size:.8rem;color:rgba(245,230,200,.55);">
          {{ $inscription->stripe_pi_id ?? $inscription->paypal_order_id ?? $inscription->id }}
        </span>
      </div>
    </div>

    <p class="facture-note">
      ⚠️ TVA non applicable — Art. 293B du CGI<br>
      Ta facture officielle sera jointe à cet email.
    </p>

    <p>
      Un second email va t'arriver dans quelques minutes avec toutes tes prochaines étapes.
      Si tu as des questions, réponds directement à cet email.
    </p>

    <a href="https://renait-sens.com/carnet" class="cta-btn">
      Accéder au Carnet de Traversée →
    </a>
  </div>

  <div class="footer">
    <p>
      Renaît-Sens · Tassili n'Ajjer · Algérie<br>
      Le sahara ne t'a pas changé — il t'a révélé.<br><br>
      <a href="#" style="color:rgba(201,169,110,.4);font-size:.72rem;">Se désinscrire</a>
    </p>
  </div>
</div>
</body>
</html>
