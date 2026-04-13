window.goStep2 =goStep2;
window.goStep1 =goStep1;
window.selectPay =selectPay;
window.selectFraction =selectFraction;
window.submitPay =submitPay;
// ══ MODE ══
function setMode(m){
  document.documentElement.setAttribute('data-mode',m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on',b.dataset.m===m));
}
(function(){const h=new Date().getHours();if(h>=9&&h<19)setMode('noon');else setMode('night');})();

// ══ CURSEUR ══
const cur=document.getElementById('cur'),curR=document.getElementById('cur-r');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// ══ DONNÉES TRAVERSÉES ══
const TRAVERSEES = {
  regard: {
    nom:'Traversée Regard', tagline:'Se poser, cesser de fuir et enfin se voir.',
    metal:'Bronze', metalClass:'metal-bronze', badge:'✦ Bronze',
    prix:800, prixLabel:'800 €',
    fractions: [], stripeKey:'price_regard_800'
  },
  presence: {
    nom:'Traversée Présence', tagline:'Habiter son corps, ses sens et chaque instant.',
    metal:'Argent', metalClass:'metal-argent', badge:'✦ Argent',
    prix:1400, prixLabel:'1 400 €',
    fractions:['2x','3x'], stripeKey:'price_presence_1400'
  },
  absolu: {
    nom:'Traversée Absolu', tagline:'Le dépouillement total pour la renaissance ultime.',
    metal:'Or', metalClass:'metal-or', badge:'✦ Or',
    prix:4000, prixLabel:'4 000 €',
    fractions:['acompte'], stripeKey:'price_absolu_4000'
  }
};

// Lire paramètre URL
const params = new URLSearchParams(window.location.search);
const choix = params.get('choix') || 'regard';
const trav = TRAVERSEES[choix] || TRAVERSEES.regard;

// Remplir récap
document.getElementById('recap-badge').textContent = trav.badge;
document.getElementById('recap-name').textContent = trav.nom;
document.getElementById('recap-tagline').textContent = trav.tagline;
document.getElementById('recap-price').textContent = trav.prixLabel;
document.getElementById('metal-bar').className = 'recap-metal-bar ' + trav.metalClass;
document.getElementById('field-traversee').value = choix;
document.getElementById('field-montant').value = trav.prix;

// Fractions
const fracWrap = document.getElementById('fraction-wrap');
if(trav.fractions.length > 0){
  fracWrap.classList.add('visible');
  document.getElementById('frac-detail-comptant').textContent = trav.prixLabel;
  if(trav.fractions.includes('2x')){
    document.getElementById('frac-2x').style.display='flex';
    document.getElementById('frac-detail-2x').textContent = '2 × '+Math.round(trav.prix/2)+' €';
  }
  if(trav.fractions.includes('3x')){
    document.getElementById('frac-3x').style.display='flex';
    document.getElementById('frac-detail-3x').textContent = '3 × '+Math.round(trav.prix/3)+' €';
  }
  if(trav.fractions.includes('acompte')){
    document.getElementById('frac-acompte').style.display='flex';
  }
}

// ══ NAVIGATION ÉTAPES ══
function goStep2(){
  if(!validateStep1()) return;
  document.getElementById('form-step-1').style.display='none';
  document.getElementById('form-step-2').style.display='block';
  document.getElementById('step-1').classList.remove('active');
  document.getElementById('step-1').classList.add('done');
  document.getElementById('step-2').classList.add('active');
  document.querySelector('.nav-step').textContent = 'Étape 2 sur 2 — Paiement';
  window.scrollTo(0,0);
}

function goStep1(){
  document.getElementById('form-step-2').style.display='none';
  document.getElementById('form-step-1').style.display='block';
  document.getElementById('step-2').classList.remove('active');
  document.getElementById('step-1').classList.remove('done');
  document.getElementById('step-1').classList.add('active');
  document.querySelector('.nav-step').textContent = 'Étape 1 sur 2 — Inscription';
}

// ══ VALIDATION ÉTAPE 1 ══
function validateStep1(){
  let ok=true;
  const fields=[
    {id:'f-prenom',  name:'prenom',    type:'text'},
    {id:'f-nom',     name:'nom',       type:'text'},
    {id:'f-email',   name:'email',     type:'email'},
    {id:'f-adresse', name:'adresse',   type:'text'},
    {id:'f-tel',     name:'telephone', type:'text'},
  ];
  fields.forEach(f=>{
    const el=document.getElementById(f.id);
    const inp=el.querySelector('input');
    const val=inp.value.trim();
    if(!val || (f.type==='email' && !/^[^@]+@[^@]+\.[^@]+$/.test(val))){
      el.classList.add('error'); ok=false;
    } else {
      el.classList.remove('error');
    }
  });
  return ok;
}

// ══ SÉLECTION PAIEMENT ══
function selectPay(el,val){
  document.querySelectorAll('.pay-method').forEach(m=>m.classList.remove('selected'));
  el.classList.add('selected');
}

function selectFraction(el,val){
  document.querySelectorAll('.frac-opt').forEach(f=>f.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('field-mode-stripe').value=val;
  const note=document.getElementById('acompte-note');
  note.classList.toggle('visible', val==='acompte');
}

// ══ SOUMISSION ══
function submitPay(e){
  e.preventDefault();
  if(!document.getElementById('cgv').checked){
    alert('Merci d\'accepter les CGV pour continuer.');
    return;
  }
  const methode = document.querySelector('input[name="methode"]:checked').value;
  const fraction = document.querySelector('input[name="fraction"]:checked')?.value || 'comptant';
  const btn = document.getElementById('btn-pay');
  btn.disabled=true;
  document.getElementById('btn-pay-txt').textContent='Redirection en cours...';

  // Collecter les données du form
  const form = document.getElementById('inscription-form');
  const formData = new FormData(form);
  formData.append('methode_paiement', methode);
  formData.append('fraction', fraction);
  formData.append('traversee', choix);

  // POST vers Laravel
  fetch('/inscription/checkout', {
    method:'POST',
    headers:{'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''},
    body: formData
  })
  .then(r=>r.json())
  .then(data=>{
    if(data.url){ window.location.href = data.url; }
    else{ alert('Erreur : '+data.message); btn.disabled=false; document.getElementById('btn-pay-txt').textContent='Procéder au paiement sécurisé'; }
  })
  .catch((e)=>{
    btn.disabled=false;
    document.getElementById('btn-pay-txt').textContent='Procéder au paiement sécurisé';
    console.error(e);
    alert('Une erreur est survenue. Veuillez réessayer.');
  });
}