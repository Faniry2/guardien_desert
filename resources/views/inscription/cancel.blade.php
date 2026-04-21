<!DOCTYPE html>
<html lang="fr" data-mode="night">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Renaît-Sens — Paiement annulé</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
[data-mode="night"]{
  --bg:#03020A;--bg2:#07060F;
  --txt:rgba(245,235,210,.95);--txt2:rgba(245,235,210,.65);
  --or:#C9A96E;--or-vif:#E8C97A;--or-sombre:#8B6530;
  --or-glow:rgba(201,169,110,.4);
  --card:rgba(7,6,15,.85);--border:rgba(201,169,110,.18);
}
[data-mode="noon"]{
  --bg:#FAF7F0;--bg2:#F2EDE2;
  --txt:#1A0A00;--txt2:rgba(40,20,0,.65);
  --or:#8B5020;--or-vif:#A06030;--or-sombre:#6B3A10;
  --or-glow:rgba(139,80,32,.3);
  --card:rgba(255,252,245,.95);--border:rgba(92,58,16,.15);
}
body{font-family:'Cormorant Garamond',Georgia,serif;background:var(--bg);color:var(--txt);
  min-height:100vh;overflow-x:hidden;cursor:none;transition:background 1.2s,color 1s;
  display:flex;align-items:center;justify-content:center;}
body::before{content:'';position:fixed;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--or-sombre),var(--or-vif),var(--or-sombre),transparent);
  z-index:9999;pointer-events:none;}
.grain{position:fixed;inset:0;z-index:1;pointer-events:none;opacity:.025;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px}
.cur{position:fixed;width:7px;height:7px;background:var(--or);border-radius:50%;
  pointer-events:none;z-index:9999;transform:translate(-50%,-50%);box-shadow:0 0 10px var(--or-glow);}
.cur-r{position:fixed;width:28px;height:28px;border:1px solid rgba(201,169,110,.35);
  border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);}

.msw-wrap{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);z-index:500;
  display:flex;gap:.35rem;background:rgba(0,0,0,.35);backdrop-filter:blur(14px);
  border:1px solid rgba(255,255,255,.08);border-radius:50px;padding:.3rem;}
[data-mode="noon"] .msw-wrap{background:rgba(255,255,255,.5);border-color:rgba(92,58,16,.2)}
.msw{border:none;border-radius:50px;background:transparent;padding:.3rem .78rem;
  font-family:'Philosopher',serif;font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;
  cursor:pointer;transition:all .3s;color:rgba(245,235,210,.45);}
[data-mode="noon"] .msw{color:rgba(15,7,0,.45)}
.msw.on{background:rgba(201,169,110,.18);color:var(--or-vif)!important;}
[data-mode="noon"] .msw.on{background:rgba(92,58,16,.14);color:#5a3010!important;}

.card{position:relative;z-index:10;max-width:560px;width:90%;
  background:var(--card);border:1px solid var(--border);
  padding:3.5rem 3rem;text-align:center;backdrop-filter:blur(20px);}
.card::before,.card::after{content:'';position:absolute;width:25px;height:25px;
  border-color:var(--or-sombre);border-style:solid;opacity:.5;}
.card::before{top:-1px;left:-1px;border-width:2px 0 0 2px;}
.card::after{bottom:-1px;right:-1px;border-width:0 2px 2px 0;}

.cancel-icon{width:70px;height:70px;border-radius:50%;
  border:1px solid rgba(201,169,110,.3);background:rgba(201,169,110,.05);
  display:flex;align-items:center;justify-content:center;
  font-size:1.8rem;margin:0 auto 2rem;color:var(--or);}

.eyebrow{font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.55em;
  text-transform:uppercase;color:var(--or);margin-bottom:1rem;display:block;}
.cancel-title{font-family:'Cinzel',serif;font-size:clamp(1.4rem,2.5vw,2rem);
  font-weight:400;line-height:1.2;color:var(--txt);margin-bottom:.5rem;}
.cancel-title em{display:block;font-family:'Cormorant Garamond',serif;
  font-style:italic;font-weight:300;font-size:.5em;color:var(--or);
  letter-spacing:.18em;margin-top:.5rem;}

.sep{display:flex;align-items:center;gap:1rem;margin:1.8rem auto;max-width:300px;}
.sep::before,.sep::after{content:'';flex:1;height:1px;
  background:linear-gradient(90deg,transparent,var(--or-sombre),transparent);}
.sep-gem{width:6px;height:6px;background:var(--or);transform:rotate(45deg);flex-shrink:0;}

.cancel-text{font-size:1rem;font-style:italic;font-weight:300;
  color:var(--txt2);line-height:1.85;margin-bottom:2rem;}

.btns{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;}
.btn-or{display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 1.8rem;
  font-family:'Cinzel',serif;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;
  text-decoration:none;color:var(--or-vif);border:1px solid var(--or);
  background:transparent;position:relative;overflow:hidden;transition:all .5s;cursor:pointer;}
.btn-or::before{content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(201,169,110,0),rgba(201,169,110,.18),rgba(201,169,110,0));
  transform:translateX(-100%);transition:transform .6s;}
.btn-or:hover::before{transform:translateX(100%);}
.btn-or:hover{background:rgba(201,169,110,.12);box-shadow:0 0 28px var(--or-glow);transform:translateY(-1px);}
.btn-ghost{display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 1.8rem;
  font-family:'Cinzel',serif;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;
  text-decoration:none;color:var(--txt2);border:1px solid var(--border);
  background:transparent;transition:all .4s;cursor:pointer;}
.btn-ghost:hover{border-color:var(--or);color:var(--or-vif);}

.desert-quote{margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(201,169,110,.1);
  font-size:.85rem;font-style:italic;color:rgba(201,169,110,.45);}

@media(max-width:600px){
  .card{padding:2.5rem 1.5rem;}
  .btns{flex-direction:column;align-items:center;}
}
</style>
</head>
<body>
<div class="cur" id="cur"></div>
<div class="cur-r" id="cur-r"></div>
<div class="grain"></div>

<div class="msw-wrap">
  <button class="msw on" data-m="night" onclick="setMode('night')">🌙 Nuit</button>
  <button class="msw"    data-m="noon"  onclick="setMode('noon')">🌞 Jour</button>
</div>

<div class="card">

  <div class="cancel-icon">🌵</div>

  <span class="eyebrow">· Paiement interrompu ·</span>

  <h1 class="cancel-title">
    Le désert t'attend encore.
    <em>Rien n'est perdu — tout recommence.</em>
  </h1>

  <div class="sep"><div class="sep-gem"></div></div>

  <p class="cancel-text">
    Ton paiement n'a pas été finalisé.<br>
    Ta place n'est pas encore sécurisée.<br><br>
    Si tu as rencontré un problème ou si tu as des questions,
    ta Sentinelle est là pour t'accompagner.
  </p>

  <div class="btns">
    <a href="{{ route('traversees') }}" class="btn-or">↩ Reprendre ma Traversée</a>
    <a href="mailto:contact@renait-sens.com" class="btn-ghost">✉ Contacter une Sentinelle</a>
  </div>

  <p class="desert-quote">« Le sable s'efface. La dune, elle, reste. »</p>

</div>

<script>
function setMode(m){
  document.documentElement.setAttribute('data-mode',m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on',b.dataset.m===m));
}
(function(){const h=new Date().getHours();if(h>=9&&h<19)setMode('noon');else setMode('night');})();
const cur=document.getElementById('cur'),curR=document.getElementById('cur-r');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();
</script>
</body>
</html>
