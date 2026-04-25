@extends('layouts.app')

@section('title','Renait-Sens — Page d\'inscription')

@push("styles")
  @vite(['resources/css/form.css'])
@endpush


@section('content')
   <div class="cur" id="cur"></div>
<div class="cur-r" id="cur-r"></div>
<div class="grain"></div>

<!-- MODE SWITCHER -->
<div class="msw-wrap">
  <button class="msw on" data-m="night" onclick="setMode('night')">🌙 Nuit</button>
  <button class="msw"    data-m="noon"  onclick="setMode('noon')">🌞 Jour</button>
</div>

<!-- NAV -->
<nav id="nav">
  <a href="{{ route('home') }}" class="nav-brand">Renait-Sens</a>
  <span class="nav-step">Étape 1 sur 2 — Inscription</span>
</nav>

<!-- LAYOUT -->
<div class="page-layout">

  <!-- GAUCHE -->
  <div class="page-left">
    <div class="left-img">
      <div class="left-recap">
        <div class="recap-metal-bar" id="metal-bar"></div>
        <span class="recap-label" id="recap-badge">✦ Bronze</span>
        <div class="recap-title" id="recap-name">Traversée Regard</div>
        <div class="recap-sub" id="recap-tagline">Se poser, cesser de fuir et enfin se voir.</div>
        <div class="recap-price-box">
          <span class="recap-price-label">L'Offrande</span>
          <span class="recap-price-val" id="recap-price">800 €</span>
        </div>
      </div>
    </div>
  </div>

  <!-- DROITE — FORMULAIRE -->
  <div class="page-right">
    <div class="form-wrap">

      <!-- STEPS -->
      <div class="steps">
        <div class="step active" id="step-1">
          <div class="step-dot">1</div>
          <span class="step-lbl">Identité</span>
        </div>
        <div class="step" id="step-2">
          <div class="step-dot">2</div>
          <span class="step-lbl">Paiement</span>
        </div>
      </div>

      <!-- FORMULAIRE -->
      <form id="inscription-form" novalidate>
        <input type="hidden" name="traversee" id="field-traversee" value="">
        <input type="hidden" name="montant" id="field-montant" value="">
        <input type="hidden" name="mode_paiement_stripe" id="field-mode-stripe" value="comptant">

        <!-- ÉTAPE 1 — IDENTITÉ -->
        <div id="form-step-1">
          <div class="form-section-title">Dis-nous qui tu es</div>
          <div class="form-section-sub">Ces informations sont strictement confidentielles.</div>

          <div class="form-row">
            <div class="field" id="f-prenom">
              <label>Prénom <span class="req">*</span></label>
              <input type="text" name="prenom" placeholder="Ton prénom" autocomplete="given-name">
              <span class="field-err">Ce champ est requis</span>
            </div>
            <div class="field" id="f-nom">
              <label>Nom <span class="req">*</span></label>
              <input type="text" name="nom" placeholder="Ton nom" autocomplete="family-name">
              <span class="field-err">Ce champ est requis</span>
            </div>
          </div>

          <div class="form-row full">
            <div class="field" id="f-email">
              <label>Email <span class="req">*</span></label>
              <input type="email" name="email" placeholder="ton@email.com" autocomplete="email">
              <span class="field-err">Email invalide</span>
            </div>
          </div>

          <div class="form-row full">
            <div class="field" id="f-adresse">
              <label>Adresse complète <span class="req">*</span></label>
              <input type="text" name="adresse" placeholder="Rue, ville, pays" autocomplete="street-address">
              <span class="field-err">Ce champ est requis</span>
            </div>
          </div>

          <div class="form-row">
            <div class="field" id="f-tel">
              <label>Téléphone <span class="req">*</span></label>
              <input type="tel" name="telephone" placeholder="+33 6 00 00 00 00" autocomplete="tel">
              <span class="field-err">Ce champ est requis</span>
            </div>
            <div class="field" id="f-whatsapp">
              <label>WhatsApp <span class="opt">(recommandé)</span></label>
              <input type="tel" name="whatsapp" placeholder="+33 6 00 00 00 00">
              <div class="whatsapp-hint">Privilégié pour l'onboarding</div>
            </div>
          </div>

          <div class="form-row full">
            <div class="field">
              <label>Comment as-tu découvert Renait-Sens ? <span class="opt">(optionnel)</span></label>
              <select name="source">
                <option value="">— Choisir —</option>
                <option value="instagram">Instagram</option>
                <option value="bouche">Bouche à oreille</option>
                <option value="google">Google</option>
                <option value="linkedin">LinkedIn</option>
                <option value="autre">Autre</option>
              </select>
            </div>
          </div>

          <div class="sep"><div class="sep-gem"></div></div>

          <!-- BOUTON SUIVANT -->
          <button type="button" class="btn-submit" onclick="goStep2()">
            <span>Continuer vers le paiement</span>
            <span>→</span>
          </button>
        </div>

        <!-- ÉTAPE 2 — PAIEMENT -->
        <div id="form-step-2" style="display:none;">
          <div class="form-section-title">Sécurise ta place</div>
          <div class="form-section-sub" id="payment-sub">Choisis ton mode de paiement.</div>

          <!-- OPTIONS FRACTIONNEMENT (Présence + Premium) -->
          <div class="fraction-options" id="fraction-wrap">
            <label class="frac-opt selected" onclick="selectFraction(this,'comptant')">
              <input type="radio" name="fraction" value="comptant" checked>
              <span class="frac-label">Paiement comptant</span>
              <span class="frac-detail" id="frac-detail-comptant">1 400 €</span>
            </label>
            <label class="frac-opt" id="frac-2x" onclick="selectFraction(this,'2x')" style="display:none;">
              <input type="radio" name="fraction" value="2x">
              <span class="frac-label">2 fois</span>
              <span class="frac-detail" id="frac-detail-2x">2 × 700 €</span>
            </label>
            <label class="frac-opt" id="frac-3x" onclick="selectFraction(this,'3x')" style="display:none;">
              <input type="radio" name="fraction" value="3x">
              <span class="frac-label">3 fois</span>
              <span class="frac-detail" id="frac-detail-3x">3 × 467 €</span>
            </label>
            <label class="frac-opt" id="frac-acompte" onclick="selectFraction(this,'acompte')" style="display:none;">
              <input type="radio" name="fraction" value="acompte">
              <span class="frac-label">Acompte + échéancier</span>
              <span class="frac-detail">1 000 € + 3 × 1 000 €</span>
            </label>
          </div>

          <!-- Note acompte Premium -->
          <div class="acompte-note" id="acompte-note">
            ⚠️ Un acompte de <strong>1 000 €</strong> est requis pour réserver ta place.<br>
            L'accès complet est conditionné au paiement de l'acompte.<br>
            Un contrat d'engagement te sera envoyé par email.
          </div>

          <!-- MÉTHODE DE PAIEMENT -->
          <div style="font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.35em;text-transform:uppercase;color:var(--or);margin-bottom:1rem;opacity:.85;">
            Méthode de paiement
          </div>
          <div class="payment-methods">
            <label class="pay-method selected" onclick="selectPay(this,'stripe')">
              <input type="radio" name="methode" value="stripe" checked>
              <span class="pay-icon">💳</span>
              <div>
                <div class="pay-name">Stripe</div>
                <div class="pay-desc">CB · Apple Pay · Google Pay</div>
              </div>
              <div class="pay-check"></div>
            </label>
            {{-- <label class="pay-method" onclick="selectPay(this,'paypal')">
              <input type="radio" name="methode" value="paypal">
              <span class="pay-icon">🅿️</span>
              <div>
                <div class="pay-name">PayPal</div>
                <div class="pay-desc">Compte PayPal · Backup</div>
              </div>
              <div class="pay-check"></div>
            </label> --}}
          </div>

          <!-- CGV -->
          <div class="cgv-wrap">
            <input type="checkbox" id="cgv" name="cgv">
            <span class="cgv-txt">
              J'accepte les <a href="#">Conditions Générales de Vente</a> et la
              <a href="#">Politique de confidentialité</a> de Renait-Sens.
              Je comprends que mon inscription sera définitive après paiement.
            </span>
          </div>

          <!-- BOUTON PAYER -->
          <button type="submit" class="btn-submit" id="btn-pay" onclick="submitPay(event)">
            <span id="btn-pay-txt">Procéder au paiement sécurisé</span>
            <span>🔒</span>
          </button>

          <!-- Sécurité -->
          <div class="security-badges">
            <span class="sec-badge">🔒 Paiement sécurisé SSL</span>
            <span class="sec-badge">🛡️ Données chiffrées</span>
            <span class="sec-badge">✅ Facture automatique</span>
          </div>

          <!-- Retour -->
          <div style="text-align:center;margin-top:1.5rem;">
            <button type="button" onclick="goStep1()"
              style="background:none;border:none;cursor:pointer;font-family:'Philosopher',serif;
              font-size:.78rem;letter-spacing:.15em;color:var(--txt2);text-decoration:underline;">
              ← Retour à l'étape précédente
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div> 
@endsection

@push("scripts")
  @vite(['resources/js/form.js'])
@endpush
{{-- <!DOCTYPE html>
<html lang="fr" data-mode="night">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Renait-Sens — Inscription</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>

</style>
</head>
<body>



<script>

</script>
</body>
</html> --}}