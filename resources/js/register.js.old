/**
 * ══════════════════════════════════════════════════════════════════
 *  RENAIT-SENS — Logique formulaire
 *  1. Pré-remplissage des hidden au chargement
 *  2. Enrichissement des hidden traversée à chaque changement de radio
 *  3. Enrichissement complet + validation au submit
 * ══════════════════════════════════════════════════════════════════
 */
(function () {

  // ─── Catalogue traversées (source de vérité côté JS) ───────────
  const TRAVERSEES = {
    regard: {
      label : 'Regard',
      prix  : '800 €',
      tag   : 'Se poser, cesser de fuir et enfin se voir.',
      feats : '3 Dialogues à cœur ouvert|Le Lien — fil direct Sentinelle|Carnet de la traversée|Une méditation par module et rythme quotidien des nomades',
      sens  : 'Passer de l\'aveuglement à la clarté.',
    },
    presence: {
      label : 'Présence',
      prix  : '1 400 €',
      tag   : 'Habiter son corps, ses sens et chaque instant.',
      feats : '6 Dialogues profonds|L\'Oasis — espace Nomades|EFT — rituels sensoriels à pratiquer chez soi|Exercice du jour — atelier en live avec la sentinelle, une méditation par module et rythme quotidien des nomades',
      sens  : 'Ne plus subir, mais habiter pleinement.',
    },
    absolu: {
      label : 'Absolu',
      prix  : 'Prix sur demande',
      tag   : 'Le dépouillement total pour la renaissance ultime.',
      feats : 'L\'Appel du Sahara — Djanet|Le Silence — 7 jours d\'immersion|La Mue — accompagnement quotidien|Accès aux 2 oasis',
      sens  : 'Revenir transformé à jamais.',
    },
  };

  // ─── Remplir les hiddens traversée ─────────────────────────────
  function syncTraverseeHiddens(value) {
    const t = TRAVERSEES[value] || {};
    document.getElementById('traversee_label').value = t.label  || '';
    document.getElementById('traversee_prix').value  = t.prix   || '';
    document.getElementById('traversee_tag').value   = t.tag    || '';
    document.getElementById('traversee_feats').value = t.feats  || '';
    document.getElementById('traversee_sens').value  = t.sens   || '';

    // Visuel carte
    document.querySelectorAll('.trav-card').forEach(card => {
      card.classList.toggle('selected', card.querySelector('input[type=radio]').value === value);
    });

    // Pill de confirmation
    const pill = document.getElementById('confirm-pill');
    if (pill && t.label) {
      document.getElementById('confirm-name').textContent = t.label;
      document.getElementById('confirm-prix').textContent = t.prix;
      document.getElementById('confirm-sens').textContent = t.sens;
      pill.classList.add('visible');
    }
  }

  window.resetChoice = function () {
    document.querySelectorAll('.trav-card').forEach(c => c.classList.remove('selected'));
    document.querySelectorAll('input[name="traversee"]').forEach(r => r.checked = false);
    document.getElementById('confirm-pill')?.classList.remove('visible');
    ['traversee_label','traversee_prix','traversee_tag','traversee_feats','traversee_sens']
      .forEach(id => { document.getElementById(id).value = ''; });
  };

  // ─── Écouter les radios traversée ──────────────────────────────
  document.querySelectorAll('input[name="traversee"]').forEach(radio => {
    radio.addEventListener('change', () => syncTraverseeHiddens(radio.value));
  });

  // ─── Résolution de la traversée initiale ───────────────────────
  //
  //  Priorité :
  //    1. Paramètre URL  ?choix=regard|presence|absolu
  //    2. Radio déjà coché (old() Laravel après erreur de validation)
  //    3. Défaut : "presence" (le plus choisi — 1 400 €)
  //
  const urlParams   = new URLSearchParams(window.location.search);
  const choixURL    = urlParams.get('choix')?.toLowerCase().trim();  // ex: "presence"
  const choixValide = choixURL && TRAVERSEES[choixURL] ? choixURL : null;

  if (choixValide) {
    // Cocher le bon radio programmatiquement
    const targetRadio = document.querySelector(`input[name="traversee"][value="${choixValide}"]`);
    if (targetRadio) {
      targetRadio.checked = true;
    }
    syncTraverseeHiddens(choixValide);
  } else {
    // Pas de paramètre URL valide → on regarde si un radio est déjà coché (old() Laravel)
    const checkedRadio = document.querySelector('input[name="traversee"]:checked');
    syncTraverseeHiddens(checkedRadio ? checkedRadio.value : 'presence');
  }

  // ─── Données navigateur au chargement ──────────────────────────
  document.getElementById('timezone').value =
    Intl.DateTimeFormat().resolvedOptions().timeZone || '';

  document.getElementById('locale').value =
    navigator.language || navigator.userLanguage || '';

  document.getElementById('source').value =
    urlParams.get('utm_source') || document.referrer || 'direct';

  // ─── Sync thème → hidden ────────────────────────────────────────
  const origSetMode = window.setMode;
  window.setMode = function (mode) {
    if (typeof origSetMode === 'function') origSetMode(mode);
    document.getElementById('theme_preference').value = mode;
  };

  // ─── Enrichissement + validation au submit ──────────────────────
  document.getElementById('register-form').addEventListener('submit', function (e) {

    // 1. Timestamp ISO
    document.getElementById('registered_at_client').value = new Date().toISOString();

    // 2. Téléphone complet
    const prefix = document.getElementById('tel-prefix').value;
    const num    = document.getElementById('telephone').value.trim();
    document.getElementById('telephone_full').value       = num ? prefix + ' ' + num : '';
    document.getElementById('tel_indicatif_hidden').value = prefix;

    // 3. Adresse complète
    const rue    = document.getElementById('rue').value.trim();
    const ville  = document.getElementById('ville').value.trim();
    const region = document.getElementById('region').value.trim();
    const cp     = document.getElementById('codepostal').value.trim();
    const pays   = document.getElementById('pays').value;
    document.getElementById('adresse_complete').value =
      [rue, cp, ville, region, pays].filter(Boolean).join(', ');

    // 4. Pacte de l'Aman
    const checked = document.getElementById('aman').checked;
    document.getElementById('pacte_aman_hidden').value = checked ? '1' : '0';

    // 5. Validation traversée (re-sync au cas où)
    const activeRadio = document.querySelector('input[name="traversee"]:checked');
    if (activeRadio) syncTraverseeHiddens(activeRadio.value);

    // 6. Validations frontales obligatoires
    const prenom    = document.getElementById('prenom').value.trim();
    const nom       = document.getElementById('nom').value.trim();
    const email     = document.getElementById('email').value.trim();
    const traversee = activeRadio ? activeRadio.value : null;

    if (!prenom || !nom || !email) {
      e.preventDefault();
      alert('Merci de remplir tous les champs obligatoires (Prénom, Nom, Email).');
      return;
    }
    if (!traversee) {
      e.preventDefault();
      alert('Choisis ta Traversée avant de rejoindre la Caravane.');
      return;
    }
    if (!checked) {
      e.preventDefault();
      alert('Tu dois accepter le Pacte de l\'Aman pour rejoindre la Caravane.');
      return;
    }
  });

})();