<!DOCTYPE html>
<html lang="fr" data-mode="night">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Renait-Sens — Les Traversées</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}

/* ══ PALETTES MODES ══ */
[data-mode="night"]{
  --sky-a:#03030F;--sky-b:#0A0820;--sky-c:#1A1230;
  --body-bg:#07071A;--body-bg2:#05050F;
  --txt:#F5E6C8;--txt2:#E0C080;--txt3:rgba(245,230,200,.82);--txt4:rgba(245,230,200,.50);
  --acc:#D4622A;--acc2:#F07A3A;--acc-glow:rgba(212,98,42,.38);
  --card-bg:rgba(10,9,28,.82);--card-border:rgba(201,169,110,.16);--card-hover:rgba(201,169,110,.28);
  --badge-bg:rgba(201,169,110,.08);--badge-border:rgba(201,169,110,.22);
  --feat-border:rgba(201,169,110,.14);
  --t-primary:#F5E6C8;--t-secondary:#E0C080;--t-muted:rgba(245,230,200,.82);
}
[data-mode="dawn"]{
  --sky-a:#1A0E22;--sky-b:#5C2D52;--sky-c:#E8845A;
  --body-bg:#FAF5EC;--body-bg2:#F5EEE0;
  --txt:#0F0700;--txt2:#3D1C00;--txt3:#1A0A00;--txt4:rgba(15,7,0,.50);
  --acc:#E05A20;--acc2:#C04010;--acc-glow:rgba(224,90,32,.38);
  --card-bg:rgba(255,252,245,.92);--card-border:rgba(100,50,0,.16);--card-hover:rgba(100,50,0,.28);
  --badge-bg:rgba(100,50,0,.06);--badge-border:rgba(100,50,0,.18);
  --feat-border:rgba(100,50,0,.12);
  --t-primary:#0F0700;--t-secondary:#3D1C00;--t-muted:#1A0A00;
}
[data-mode="noon"]{
  --sky-a:#4A8FD4;--sky-b:#82BBE8;--sky-c:#C8E0F0;
  --body-bg:#F5F0E8;--body-bg2:#EDE8DF;
  --txt:#0F0700;--txt2:#3D1C00;--txt3:#1A0A00;--txt4:rgba(15,7,0,.50);
  --acc:#C04A10;--acc2:#A03800;--acc-glow:rgba(192,74,16,.38);
  --card-bg:rgba(255,255,255,.92);--card-border:rgba(92,58,16,.14);--card-hover:rgba(92,58,16,.26);
  --badge-bg:rgba(92,58,16,.06);--badge-border:rgba(92,58,16,.18);
  --feat-border:rgba(92,58,16,.12);
  --t-primary:#0F0700;--t-secondary:#3D1C00;--t-muted:#1A0A00;
}

body{
  font-family:'Cormorant Garamond',Georgia,serif;
  font-size:17px;
  background:var(--body-bg);
  color:var(--t-primary);
  overflow-x:hidden;cursor:none;
  transition:background 1.8s,color 1.4s;
}
body::before{
  content:'';position:fixed;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,#8B6530,#C9A96E,#8B6530,transparent);
  z-index:9999;pointer-events:none;
}

/* ══ FOND HERO PAR MODE ══ */
body::after{
  content:'';
  position:fixed;inset:0;
  z-index:-1;
  background-size:cover;
  background-position:center;
  background-repeat:no-repeat;
  transition:opacity 1.8s;
  opacity:.18;
}
[data-mode="night"] body::after{ background-image:url('/images/hero-night.png'); }
[data-mode="dawn"]  body::after{ background-image:url('/images/hero-dawn.png');  }
[data-mode="noon"]  body::after{ background-image:url('/images/hero-noon.png');  }

/* ══ CURSEUR CUSTOM ══ */
.cur{
  position:fixed;width:14px;height:14px;
  border-radius:50%;pointer-events:none;z-index:9999;
  transform:translate(-50%,-50%);
  transition:width .3s,height .3s;
}
.cur-r{
  position:fixed;width:32px;height:32px;
  border:1px solid rgba(212,98,42,.35);
  border-radius:50%;pointer-events:none;z-index:9998;
  transform:translate(-50%,-50%);
  transition:width .3s,height .3s;
}

/* curseur par défaut */
[data-mode="night"] .cur{ background:rgba(212,98,42,.6); box-shadow:0 0 8px rgba(212,98,42,.4); }
[data-mode="dawn"]  .cur{ background:rgba(224,90,32,.6); box-shadow:0 0 8px rgba(224,90,32,.4); }
[data-mode="noon"]  .cur{ background:rgba(192,74,16,.6); box-shadow:0 0 8px rgba(192,74,16,.4); }

/* canvas curseur flamme */
#flame-cursor{
  position:fixed;
  inset:0;
  width:100vw;
  height:100vh;
  pointer-events:none;
  z-index:9996;
  display:none;
}

/* ══ GRAIN ══ */
.grain{position:fixed;inset:0;z-index:1;pointer-events:none;opacity:.028;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px}

::-webkit-scrollbar{width:3px}
::-webkit-scrollbar-track{background:var(--body-bg)}
::-webkit-scrollbar-thumb{background:linear-gradient(180deg,#8B6530,#C9A96E,#8B6530)}
::selection{background:rgba(201,169,110,.2);color:#E8C97A}

/* ══ MODE SWITCHER — BAS GAUCHE ══ */
.msw-wrap{
  position:fixed;bottom:2rem;left:1.5rem;
  z-index:500;display:flex;gap:.35rem;
  background:rgba(0,0,0,.3);backdrop-filter:blur(14px);
  border:1px solid rgba(255,255,255,.09);border-radius:50px;padding:.3rem;
  transition:background .5s;
}
[data-mode="noon"] .msw-wrap{background:rgba(255,255,255,.45);border-color:rgba(92,58,16,.2)}
.msw{border:none;border-radius:50px;background:transparent;padding:.3rem .78rem;
  font-family:'Philosopher',serif;font-size:.67rem;letter-spacing:.12em;text-transform:uppercase;
  cursor:pointer;transition:all .3s}
[data-mode="night"] .msw{color:rgba(255,255,255,.48)}
[data-mode="dawn"]  .msw{color:rgba(255,255,255,.48)}
[data-mode="noon"]  .msw{color:rgba(15,7,0,.48)}
[data-mode="night"] .msw.on{background:rgba(255,255,255,.16);color:#fff}
[data-mode="dawn"]  .msw.on{background:rgba(255,255,255,.18);color:#fff}
[data-mode="noon"]  .msw.on{background:rgba(255,255,255,.65);color:#0F0700}

/* ══ NAV ══ */
#nav{
  position:fixed;top:0;left:0;width:100%;z-index:400;
  padding:1.5rem 3rem;display:flex;align-items:center;justify-content:space-between;
  background:linear-gradient(180deg,rgba(3,2,10,.7) 0%,transparent 100%);
  transition:all .5s;
}
#nav.scrolled{
  background:rgba(5,5,20,.92);backdrop-filter:blur(20px);
  padding:1rem 3rem;border-bottom:1px solid rgba(201,169,110,.12);
}
[data-mode="noon"] #nav.scrolled{background:rgba(245,240,232,.96);border-bottom-color:rgba(92,58,16,.12)}
.nav-brand{font-family:'Cinzel',serif;font-size:.72rem;letter-spacing:.25em;
  color:#C9A96E;text-transform:uppercase;text-decoration:none;
  text-shadow:0 0 20px rgba(201,169,110,.4);}
[data-mode="noon"] #nav.scrolled .nav-brand{color:#5a3010;text-shadow:none}
nav ul{list-style:none;display:flex;gap:2.5rem}
nav ul li a{font-family:'Philosopher',serif;font-size:.8rem;letter-spacing:.18em;
  text-transform:uppercase;text-decoration:none;color:rgba(255,255,255,.78);
  position:relative;transition:color .4s}
nav ul li a::after{content:'';position:absolute;bottom:-3px;left:0;
  width:0;height:1px;background:var(--acc);transition:width .35s}
nav ul li a:hover::after{width:100%}
nav ul li a:hover{color:var(--acc)}
[data-mode="noon"] #nav.scrolled nav ul li a{color:#1A0A00}
.nav-cta{font-family:'Philosopher',serif;font-size:.76rem;letter-spacing:.18em;
  text-transform:uppercase;padding:.48rem 1.25rem;
  border:1px solid rgba(255,255,255,.45);color:rgba(255,255,255,.9);
  text-decoration:none;transition:all .35s;background:transparent}
#nav.scrolled .nav-cta{border-color:var(--acc);color:var(--acc)}
.nav-cta:hover{background:var(--acc);border-color:var(--acc);color:#fff!important}
.burger{display:none;flex-direction:column;justify-content:center;gap:5px;
  width:36px;height:36px;background:none;border:none;cursor:pointer;padding:4px}
.burger span{display:block;height:1.5px;background:rgba(255,255,255,.85);border-radius:2px;transition:all .35s}
.burger span:nth-child(1){width:22px}
.burger span:nth-child(2){width:16px}
.burger span:nth-child(3){width:22px}
[data-mode="noon"] #nav.scrolled .burger span{background:#0F0700}
.burger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg);width:22px}
.burger.open span:nth-child(2){opacity:0;width:0}
.burger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg);width:22px}
.mob-nav{position:fixed;top:0;left:0;width:100%;height:100vh;z-index:390;
  display:flex;flex-direction:column;justify-content:center;align-items:center;gap:2.2rem;
  background:rgba(5,5,15,.96);backdrop-filter:blur(24px);
  opacity:0;pointer-events:none;transition:opacity .4s}
[data-mode="noon"] .mob-nav{background:rgba(245,240,232,.97)}
.mob-nav.open{opacity:1;pointer-events:all}
.mob-nav a{font-family:'Cinzel',serif;font-size:1.4rem;letter-spacing:.22em;
  text-transform:uppercase;text-decoration:none;color:var(--t-primary);
  opacity:0;transform:translateY(16px);transition:opacity .4s,transform .4s,color .35s}
.mob-nav.open a{opacity:1;transform:none}
.mob-nav.open a:nth-child(1){transition-delay:.06s}
.mob-nav.open a:nth-child(2){transition-delay:.13s}
.mob-nav.open a:nth-child(3){transition-delay:.2s}
.mob-nav a:hover{color:var(--acc)}

/* ══ SECTION ══ */
.section{padding:8rem 3rem;position:relative;z-index:10;background:transparent;transition:background 1.8s}
.si{max-width:1200px;margin:0 auto}
.s-label{font-family:'Philosopher',serif;font-size:.66rem;letter-spacing:.55em;
  text-transform:uppercase;color:var(--acc);opacity:.85;margin-bottom:1rem;display:block}
.s-title{font-family:'Cinzel',serif;font-size:clamp(1.8rem,3.5vw,2.8rem);
  font-weight:400;line-height:1.2;margin-bottom:1rem;transition:color 1.4s}
[data-mode="night"] .s-title{color:#F5E6C8}
[data-mode="dawn"]  .s-title,
[data-mode="noon"]  .s-title{color:#0F0700}
.s-intro{font-size:1.18rem;font-style:italic;font-weight:300;line-height:1.85;max-width:600px;transition:color 1.4s}
[data-mode="night"] .s-intro{color:#E0C080}
[data-mode="dawn"]  .s-intro,
[data-mode="noon"]  .s-intro{color:#3D1C00}

/* ══ CARDS WRAPPER ══ */
.traversees-wrap{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:2px;
  margin-top:5rem;
}

/* ══ CARD BASE ══ */
.tcard{
  position:relative;overflow:hidden;
  background:var(--card-bg);
  border:1px solid var(--card-border);
  backdrop-filter:blur(18px);
  display:flex;flex-direction:column;
  transition:border-color .5s,background 1.8s,transform .4s,box-shadow .4s;
  cursor:none;
}
.tcard:hover{transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.3);}

/* ══ BADGE ══ */
.badge{
  position:absolute;top:1.5rem;right:1.5rem;z-index:10;
  font-family:'Philosopher',serif;font-size:.6rem;letter-spacing:.28em;text-transform:uppercase;
  background:var(--badge-bg);border:1px solid var(--badge-border);
  padding:.3rem .8rem;color:#F07A3A;transition:all 1.4s;
}
[data-mode="noon"] .badge{color:#8B3A00}

/* Visuel image */
.tcard-visual{height:220px;position:relative;overflow:hidden;flex-shrink:0}
.tcard-bg{position:absolute;inset:0;background-size:cover;background-position:center;
  transition:transform .8s cubic-bezier(.25,.46,.45,.94)}
.tcard:hover .tcard-bg{transform:scale(1.05)}
.tcard-visual-overlay{position:absolute;inset:0;
  background:linear-gradient(180deg,rgba(0,0,0,.1) 0%,rgba(0,0,0,.62) 100%)}
.tcard-num{position:absolute;bottom:1.2rem;left:1.5rem;
  font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.45em;
  color:rgba(255,255,255,.68);text-transform:uppercase}

/* ══ METAL BADGE ══ */
.metal-badge{
  display:inline-flex;align-items:center;gap:.4rem;
  font-family:'Cinzel',serif;font-size:.58rem;letter-spacing:.5em;
  text-transform:uppercase;padding:.3rem 1rem;border-radius:2px;
  margin-bottom:1rem;
}
.metal-badge.bronze{
  color:#FFB347;
  background:linear-gradient(135deg,rgba(90,40,10,.5),rgba(160,82,45,.3));
  border:1px solid rgba(205,133,63,.4);
  text-shadow:0 0 12px rgba(255,160,50,.7);
}
.metal-badge.argent{
  color:#D8EAF5;
  background:linear-gradient(135deg,rgba(20,30,50,.5),rgba(60,90,130,.3));
  border:1px solid rgba(120,160,210,.35);
  text-shadow:0 0 12px rgba(160,210,245,.6);
}
.metal-badge.or{
  color:#FFD700;
  background:linear-gradient(135deg,rgba(42,24,0,.5),rgba(107,69,0,.35));
  border:1px solid rgba(255,215,0,.4);
  text-shadow:0 0 18px rgba(255,215,0,.9),0 0 35px rgba(218,165,32,.6);
  animation:or-pulse 2.5s ease-in-out infinite;
}
@keyframes or-pulse{
  0%,100%{text-shadow:0 0 18px rgba(255,215,0,.9),0 0 35px rgba(218,165,32,.6)}
  50%{text-shadow:0 0 30px rgba(255,215,0,1),0 0 60px rgba(218,165,32,.9)}
}

/* ══ PREMIUM VERTICAL — dans le visual, coin droit ══ */
.premium-tag{
  position:absolute;
  right:0;
  top:0;
  bottom:0;
  width:22px;
  display:flex;
  align-items:center;
  justify-content:center;
  writing-mode:vertical-rl;
  text-orientation:mixed;
  font-family:'Cinzel',serif;
  font-size:1rem;
  letter-spacing:.5em;
  text-transform:uppercase;
  color:rgba(255,215,0,.9);
  white-space:nowrap;
  z-index:10;
  background:rgba(0,0,0,.35);
  backdrop-filter:blur(4px);
  border-left:1px solid rgba(255,215,0,.25);
  text-shadow:0 0 12px rgba(255,215,0,.8);
  animation:premium-glow 3s ease-in-out infinite;
}
.tcard-name-wrap{display:flex;align-items:center;margin-bottom:.5rem;}
.tcard-name-wrap .tcard-name{margin-bottom:0!important;}
@keyframes premium-glow{
  0%,100%{color:rgba(255,215,0,.6);text-shadow:0 0 8px rgba(255,215,0,.4)}
  50%{color:rgba(255,215,0,.95);text-shadow:0 0 18px rgba(255,215,0,.85),0 0 35px rgba(218,165,32,.5)}
}

/* Bordures metal */
.tcard:nth-child(1){border-color:rgba(205,133,63,.3)!important;}
.tcard:nth-child(1):hover{border-color:rgba(205,133,63,.6)!important;box-shadow:0 20px 60px rgba(160,82,45,.3)!important;}
.tcard:nth-child(2){border-color:rgba(120,160,210,.25)!important;}
.tcard:nth-child(2):hover{border-color:rgba(120,160,210,.55)!important;box-shadow:0 20px 60px rgba(80,120,180,.25)!important;}
.tcard:nth-child(3){border-color:rgba(255,215,0,.28)!important;}
.tcard:nth-child(3):hover{border-color:rgba(255,215,0,.6)!important;box-shadow:0 20px 60px rgba(218,165,32,.35),0 0 80px rgba(255,215,0,.1)!important;}

/* Sweep reflet hover */
.tcard::after{content:'';position:absolute;inset:0;
  background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,.07) 45%,rgba(255,255,255,.14) 50%,rgba(255,255,255,.07) 55%,transparent 70%);
  opacity:0;transform:translateX(-120%);pointer-events:none;z-index:5;}
.tcard:hover::after{opacity:1;transform:translateX(120%);
  transition:transform .7s cubic-bezier(.25,.46,.45,.94),opacity .05s;}

/* ══ CORPS CARD ══ */
.tcard-body{padding:2rem 2rem 2.5rem;flex:1;display:flex;flex-direction:column}
.tcard-name{font-family:'Cinzel',serif;font-size:1.65rem;font-weight:400;
  margin-bottom:.5rem;letter-spacing:.04em;transition:color 1.4s}
[data-mode="night"] .tcard-name{color:#F5E6C8}
[data-mode="dawn"]  .tcard-name,
[data-mode="noon"]  .tcard-name{color:#0F0700}
.tcard-tagline{font-size:1rem;font-style:italic;font-weight:300;
  margin-bottom:1.8rem;line-height:1.6;transition:color 1.4s}
[data-mode="night"] .tcard-tagline{color:#E0C080}
[data-mode="dawn"]  .tcard-tagline,
[data-mode="noon"]  .tcard-tagline{color:#3D1C00}

/* Prix */
.tcard-price{display:flex;align-items:baseline;gap:.5rem;margin-bottom:.35rem}
.tcard-price.col{flex-direction:column;align-items:flex-start;gap:.2rem}
.price-label{font-family:'Philosopher',serif;font-size:.62rem;letter-spacing:.35em;
  text-transform:uppercase;transition:color 1.4s}
[data-mode="night"] .price-label{color:rgba(245,230,200,.65)}
[data-mode="dawn"]  .price-label,
[data-mode="noon"]  .price-label{color:rgba(15,7,0,.55)}
.price-val{font-family:'Cinzel',serif;font-size:1.6rem;font-weight:400;letter-spacing:.03em;transition:color 1.4s}
[data-mode="night"] .price-val{color:#F5E6C8}
[data-mode="dawn"]  .price-val,
[data-mode="noon"]  .price-val{color:#0F0700}
.price-demand{font-family:'Cormorant Garamond',serif;font-size:.9rem;font-style:italic;transition:color 1.4s}
[data-mode="night"] .price-demand{color:#E0C080}
[data-mode="dawn"]  .price-demand,
[data-mode="noon"]  .price-demand{color:#3D1C00}
.price-note{font-size:.78rem;font-style:italic;transition:color 1.4s}
[data-mode="night"] .price-note{color:rgba(245,230,200,.50)}
[data-mode="dawn"]  .price-note,
[data-mode="noon"]  .price-note{color:rgba(15,7,0,.50)}
.tcard-temps{font-family:'Philosopher',serif;font-size:.66rem;letter-spacing:.25em;
  text-transform:uppercase;margin-bottom:1.8rem;transition:color 1.4s}
[data-mode="night"] .tcard-temps{color:rgba(245,230,200,.65)}
[data-mode="dawn"]  .tcard-temps,
[data-mode="noon"]  .tcard-temps{color:rgba(15,7,0,.55)}
.tcard-sep{height:1px;background:var(--feat-border);margin-bottom:1.6rem;transition:background 1.4s}

/* Features */
.tcard-feats{flex:1;display:flex;flex-direction:column;gap:.95rem;margin-bottom:2rem}
.feat{display:flex;align-items:flex-start;gap:.9rem}
.feat-dot{width:5px;height:5px;border-radius:50%;background:var(--acc);flex-shrink:0;margin-top:.48rem}
.feat-title{font-family:'Cinzel',serif;font-size:.77rem;letter-spacing:.08em;margin-bottom:.3rem;transition:color 1.4s}
[data-mode="night"] .feat-title{color:#F5E6C8}
[data-mode="dawn"]  .feat-title,
[data-mode="noon"]  .feat-title{color:#0F0700}
.feat-desc{font-size:.9rem;font-style:italic;font-weight:300;line-height:1.68;transition:color 1.4s}
[data-mode="night"] .feat-desc{color:rgba(245,230,200,.80)}
[data-mode="dawn"]  .feat-desc,
[data-mode="noon"]  .feat-desc{color:#1A0A00}

/* Sens */
.tcard-sens{padding:.9rem 1.2rem;border-left:2px solid var(--acc);
  margin-bottom:2rem;background:rgba(212,98,42,.05);transition:border-color 1.4s}
.sens-label{font-family:'Philosopher',serif;font-size:.6rem;letter-spacing:.35em;
  text-transform:uppercase;color:var(--acc);opacity:.8;display:block;margin-bottom:.35rem}
.sens-txt{font-size:.92rem;font-style:italic;line-height:1.6;transition:color 1.4s}
[data-mode="night"] .sens-txt{color:#E0C080}
[data-mode="dawn"]  .sens-txt,
[data-mode="noon"]  .sens-txt{color:#3D1C00}

/* CTA */
.tcard-cta{display:block;width:100%;padding:.92rem;text-align:center;
  font-family:'Philosopher',serif;font-size:.8rem;letter-spacing:.22em;text-transform:uppercase;
  text-decoration:none;border:none;cursor:none;
  position:relative;overflow:hidden;transition:all .35s;}
.tcard-cta.primary{background:var(--acc);color:#fff;box-shadow:0 4px 20px var(--acc-glow)}
.tcard-cta.primary:hover{filter:brightness(1.1);transform:translateY(-1px);box-shadow:0 8px 28px var(--acc-glow)}
.tcard-cta.outline{background:transparent;border:1px solid var(--card-border)}
[data-mode="night"] .tcard-cta.outline{color:#E0C080}
[data-mode="dawn"]  .tcard-cta.outline,
[data-mode="noon"]  .tcard-cta.outline{color:#3D1C00}
.tcard-cta.outline:hover{border-color:var(--acc);color:var(--acc)}
.tcard-cta::before{content:'';position:absolute;inset:0;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);
  transform:translateX(-100%);transition:transform .55s}
.tcard-cta:hover::before{transform:translateX(100%)}

/* Fonds visuels cards */
.bg-bronze{background-image: url('/images/REGARD.png'); background-size: cover; background-position: center;}
.bg-argent{background-image: url('/images/PRESENCE.png'); background-size: cover; background-position: center;}
.bg-or{background-image: url('/images/absolue.png'); background-size: cover; background-position: center;}
/* ══ FOOTER ══ */
footer{background:var(--body-bg2);padding:3rem 3rem 2rem;
  border-top:1px solid var(--feat-border);position:relative;z-index:10;transition:background 1.8s;}
.ft-in{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;
  align-items:center;flex-wrap:wrap;gap:1.5rem;padding-bottom:1.5rem;
  border-bottom:1px solid var(--feat-border)}
.ft-brand{font-family:'Cinzel',serif;font-size:.67rem;letter-spacing:.25em;text-transform:uppercase;transition:color 1.4s}
[data-mode="night"] .ft-brand{color:rgba(245,230,200,.45)}
[data-mode="dawn"]  .ft-brand,
[data-mode="noon"]  .ft-brand{color:rgba(15,7,0,.45)}
.ft-tag{font-size:.88rem;font-style:italic;transition:color 1.4s}
[data-mode="night"] .ft-tag{color:rgba(245,230,200,.25)}
[data-mode="dawn"]  .ft-tag,
[data-mode="noon"]  .ft-tag{color:rgba(15,7,0,.28)}
.ft-copy{text-align:center;padding-top:1.3rem;font-family:'Philosopher',serif;
  font-size:.62rem;letter-spacing:.2em;text-transform:uppercase;max-width:1200px;margin:0 auto;transition:color 1.4s}
[data-mode="night"] .ft-copy{color:rgba(245,230,200,.18)}
[data-mode="dawn"]  .ft-copy,
[data-mode="noon"]  .ft-copy{color:rgba(15,7,0,.20)}

/* ══ REVEAL ══ */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .8s,transform .8s}
.reveal.v{opacity:1;transform:none}
.rd1{transition-delay:.12s}.rd2{transition-delay:.25s}

/* ══ CARDS MÉTAL OPAQUE ══ */
.tcard:nth-child(1){
  background:linear-gradient(160deg,#1A0A02 0%,#4A2008 18%,#8B4513 32%,#CD853F 45%,#E8A040 50%,#CD853F 55%,#8B4513 68%,#4A2008 82%,#1A0A02 100%)!important;
  border-color:rgba(205,133,63,.5)!important;
  box-shadow:inset 0 1px 0 rgba(255,200,130,.2),inset 0 -1px 0 rgba(0,0,0,.4),0 0 35px rgba(160,82,45,.25)!important;
}
.tcard:nth-child(1) .tcard-body{background:transparent!important;}
.tcard:nth-child(1):hover{border-color:rgba(255,180,80,.7)!important;box-shadow:inset 0 1px 0 rgba(255,210,140,.25),0 0 55px rgba(205,133,63,.45),0 20px 60px rgba(160,82,45,.35)!important;filter:brightness(1.06);}
.tcard:nth-child(1) .tcard-name{color:#FFF0D8!important;text-shadow:0 2px 8px rgba(0,0,0,.8)!important;}
.tcard:nth-child(1) .tcard-tagline{color:rgba(255,220,160,.82)!important;}
.tcard:nth-child(1) .price-val{color:#FFF0D8!important;}
.tcard:nth-child(1) .tcard-sep{background:rgba(255,180,80,.3)!important;}
.tcard:nth-child(1) .feat-dot{background:#FFB347!important;box-shadow:0 0 6px rgba(255,160,50,.6);}
.tcard:nth-child(1) .feat-title{color:#FFF0D8!important;}
.tcard:nth-child(1) .feat-desc{color:rgba(255,220,160,.78)!important;}
.tcard:nth-child(1) .sens-txt{color:rgba(255,220,160,.85)!important;}
.tcard:nth-child(1) .tcard-sens{border-color:rgba(255,160,50,.5)!important;background:rgba(0,0,0,.15)!important;}
.tcard:nth-child(1) .tcard-num{color:rgba(255,200,130,.7)!important;}
.tcard:nth-child(1) .tcard-cta.outline{color:#FFF0D8!important;border-color:rgba(255,180,80,.45)!important;background:rgba(0,0,0,.2)!important;}

.tcard:nth-child(2){
  background:linear-gradient(160deg,#080A12 0%,#151E2E 18%,#2E3D56 32%,#6080A8 45%,#A0C0D8 50%,#6080A8 55%,#2E3D56 68%,#151E2E 82%,#080A12 100%)!important;
  border-color:rgba(120,160,210,.45)!important;
  box-shadow:inset 0 1px 0 rgba(200,230,255,.18),inset 0 -1px 0 rgba(0,0,0,.4),0 0 35px rgba(80,120,180,.2)!important;
}
.tcard:nth-child(2) .tcard-body{background:transparent!important;}
.tcard:nth-child(2):hover{border-color:rgba(180,220,255,.65)!important;box-shadow:inset 0 1px 0 rgba(200,230,255,.22),0 0 55px rgba(100,160,220,.4),0 20px 60px rgba(60,100,160,.3)!important;filter:brightness(1.08);}
.tcard:nth-child(2) .tcard-name{color:#F0F8FF!important;}
.tcard:nth-child(2) .tcard-tagline{color:rgba(180,220,245,.82)!important;}
.tcard:nth-child(2) .price-val{color:#F0F8FF!important;}
.tcard:nth-child(2) .tcard-sep{background:rgba(120,160,210,.3)!important;}
.tcard:nth-child(2) .feat-dot{background:#A0C0D8!important;}
.tcard:nth-child(2) .feat-title{color:#F0F8FF!important;}
.tcard:nth-child(2) .feat-desc{color:rgba(180,220,245,.78)!important;}
.tcard:nth-child(2) .sens-txt{color:rgba(180,220,245,.85)!important;}
.tcard:nth-child(2) .tcard-sens{border-color:rgba(120,160,210,.5)!important;background:rgba(0,0,0,.15)!important;}
.tcard:nth-child(2) .badge{color:#D8EAF5!important;background:rgba(0,0,0,.25)!important;border-color:rgba(120,160,210,.35)!important;}
.tcard:nth-child(2) .tcard-cta.primary{background:linear-gradient(135deg,#3A5070,#6080A8)!important;box-shadow:0 4px 20px rgba(80,120,180,.5)!important;}

.tcard:nth-child(3){
  background:linear-gradient(160deg,#0D0800 0%,#2A1800 15%,#6B4500 28%,#B8860B 40%,#DAA520 46%,#FFD700 50%,#DAA520 54%,#B8860B 60%,#6B4500 72%,#2A1800 85%,#0D0800 100%)!important;
  border-color:rgba(255,215,0,.45)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,180,.22),inset 0 -1px 0 rgba(0,0,0,.5),0 0 45px rgba(218,165,32,.3),0 0 80px rgba(255,215,0,.08)!important;
}
.tcard:nth-child(3) .tcard-body{background:transparent!important;}
.tcard:nth-child(3):hover{border-color:rgba(255,215,0,.7)!important;box-shadow:inset 0 1px 0 rgba(255,255,180,.28),0 0 65px rgba(218,165,32,.55),0 0 100px rgba(255,215,0,.18),0 20px 60px rgba(160,120,0,.4)!important;filter:brightness(1.08) saturate(1.1);}
.tcard:nth-child(3) .tcard-name{color:#FFFEF0!important;}
.tcard:nth-child(3) .tcard-tagline{color:rgba(255,238,160,.85)!important;}
.tcard:nth-child(3) .price-demand{color:#FFF8DC!important;}
.tcard:nth-child(3) .tcard-sep{background:rgba(255,215,0,.3)!important;box-shadow:0 0 6px rgba(255,215,0,.2);}
.tcard:nth-child(3) .feat-dot{background:#FFD700!important;box-shadow:0 0 8px rgba(255,215,0,.7);}
.tcard:nth-child(3) .feat-title{color:#FFFEF0!important;}
.tcard:nth-child(3) .feat-desc{color:rgba(255,238,160,.78)!important;}
.tcard:nth-child(3) .sens-txt{color:rgba(255,238,160,.88)!important;}
.tcard:nth-child(3) .tcard-sens{border-color:rgba(255,215,0,.5)!important;background:rgba(0,0,0,.18)!important;}
.tcard:nth-child(3) .tcard-cta.outline{color:#FFF8DC!important;border-color:rgba(255,215,0,.45)!important;background:rgba(0,0,0,.22)!important;}

.tcard-visual-overlay{background:linear-gradient(180deg,rgba(0,0,0,.05) 0%,rgba(0,0,0,.55) 100%)!important; }

/* ══ RESPONSIVE ══ */
@media(max-width:1000px){.traversees-wrap{grid-template-columns:1fr}.tcard-visual{height:180px}}
@media(max-width:900px){
  #nav{padding:1.2rem 1.5rem}
  nav ul,#nav-cta-d{display:none}
  .burger{display:flex}
  .section{padding:5rem 1.5rem}
  .msw-wrap{left:50%;transform:translateX(-50%)}
  .tcard:nth-child(3) .premium-tag{display:none}
  .msw-wrap{bottom:1rem;left:50%;transform:translateX(-50%); display:none !important;}
}
</style>
</head>
<body>

<!-- Canvas flamme curseur -->
<canvas id="flame-cursor"></canvas>

<div class="cur" id="cur"></div>
<div class="cur-r" id="cur-r"></div>
<div class="grain"></div>

<!-- MODE SWITCHER — bas gauche -->
<div class="msw-wrap">
  <button class="msw on" data-m="night" onclick="setMode('night')">🌙 Nuit</button>
  <button class="msw"    data-m="dawn"  onclick="setMode('dawn')">🌅 Aube</button>
  <button class="msw"    data-m="noon"  onclick="setMode('noon')">🌞 Midi</button>
</div>

<!-- NAV -->
<nav id="nav">
  <a href="{{ route('home') }}" class="nav-brand">Renait-Sens</a>
  <ul>
    <li><a href="{{ route('home') }}">Le Carnet</a></li>
    <li><a href="{{ route('renait_sens') }}">Renait-Sens</a></li>
    <li><a href="{{ route('traversees') }}">Traversées</a></li>
  </ul>
  <a href="{{ route('login') }}" id="nav-cta-d" class="nav-cta">Accéder au Carnet</a>
  <button class="burger" id="burger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- MOBILE NAV -->
<div class="mob-nav" id="mob-nav">
  <a href="{{ route('home') }}" onclick="closeMenu()">Le Carnet</a>
  <a href="{{ route('renait_sens') }}" onclick="closeMenu()">Renait-Sens</a>
  <a href="{{ route('traversees') }}" onclick="closeMenu()">Traversées</a>
  <a href="{{ route('login') }}" onclick="closeMenu()">Accéder au Carnet</a>
</div>

<!-- ══ TRAVERSÉES ══ -->
<section class="section" id="traversees" style="padding-top:9rem;">
  <div class="si">
    <span class="s-label reveal">· Les Traversées ·</span>
    <h2 class="s-title reveal rd1">Trois Chemins, Une Transformation</h2>
    <p class="s-intro reveal rd2">L'Offrande n'est pas un prix. C'est un investissement dans ta renaissance.</p>

    <div class="traversees-wrap">

      <!-- ═ BRONZE — REGARD ═ -->
      <div class="tcard reveal" data-tier="bronze">
        <div class="tcard-visual">
          <div class="tcard-bg bg-bronze"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 01</span>
        </div>
        <div class="tcard-body">
          <div class="metal-badge bronze">✦ Bronze</div>
          <div class="tcard-name">Regard</div>
          <div class="tcard-tagline">Se poser, cesser de fuir et enfin se voir.</div>
          <div class="tcard-price">
            <span class="price-label">L'Offrande</span>
            <span class="price-val">800 €</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois pour ralentir le rythme</div>
          <div class="tcard-sep"></div>
          <div class="tcard-feats">
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">3 Dialogues à cœur ouvert</div>
              <div class="feat-desc">Une rencontre mensuelle avec la Sentinelle pour identifier l'essentiel.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Le Lien</div>
              <div class="feat-desc">Un fil direct avec la Sentinelle pour tes moments de doute ou de révélation.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Carnet de la traversée</div>
              <div class="feat-desc">Un écrin pour tes pensées, pour fixer tes prises de conscience.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Une méditation par module</div>
              <div class="feat-desc"></div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Rythme quotidien des nomades</div>
              <div class="feat-desc">Exercice du jour.</div>
            </div></div>
          </div>
          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Passer de l'aveuglement à la clarté.</div>
          </div>
          <a href="{{ route('inscription.form', ['choix' => 'regard']) }}" class="tcard-cta outline">Commencer ce chemin →</a>
        </div>
      </div>

      <!-- ═ ARGENT — PRÉSENCE ═ -->
      <div class="tcard reveal rd1" data-tier="argent">
        {{-- <div class="badge">Le plus choisi</div> --}}
        <div class="tcard-visual">
          <div class="tcard-bg bg-argent"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 02</span>
        </div>
        <div class="tcard-body">
          <div class="metal-badge argent">✦ Argent</div>
          <div class="tcard-name">Présence</div>
          <div class="tcard-tagline">Habiter son corps, ses sens et chaque instant de sa vie.</div>
          <div class="tcard-price">
            <span class="price-label">L'Offrande</span>
            <span class="price-val">1 400 €</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois pour s'ancrer</div>
          <div class="tcard-sep"></div>
          <div class="tcard-feats">
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">6 Dialogues profonds</div>
              <div class="feat-desc">Deux rendez-vous par mois avec la Sentinelle pour déconstruire et rebâtir.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">L'Oasis</div>
              <div class="feat-desc">L'accès à un espace de partage intime avec d'autres Nomades.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">EFT</div>
              <div class="feat-desc">Des rituels sensoriels à pratiquer chez soi pour reconnecter l'esprit au corps.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Atelier en live avec la Sentinelle</div>
              <div class="feat-desc"></div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Une méditation par module</div>
              <div class="feat-desc"></div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Rythme quotidien des nomades</div>
              <div class="feat-desc">Exercice du jour.</div>
            </div></div>
          </div>
          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Ne plus subir, mais habiter pleinement sa propre existence.</div>
          </div>
          <a href="{{ route('inscription.form', ['choix' => 'presence']) }}" class="tcard-cta primary">Choisir la Présence →</a>
        </div>
      </div>

      <!-- ═ OR — ABSOLU ═ -->
      <div class="tcard reveal rd2" data-tier="or">
        <div class="tcard-visual">
          <div class="tcard-bg bg-or"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 03</span>
          <span class="premium-tag">PREMIUM</span>
        </div>
        <div class="tcard-body">
          <div class="metal-badge or">✦ Or</div>
          <div class="tcard-name">Absolu</div>
          <div class="tcard-tagline">Le dépouillement total pour la renaissance ultime.</div>
          <div class="tcard-price col">
            <span class="price-label">L'Offrande</span>
            <span class="price-demand">Prix sur demande</span>
            <span class="price-note">L'engagement d'une vie</span>
          </div>
          <div class="tcard-temps">⏳ &nbsp;3 mois de préparation + 7 jours d'immersion</div>
          <div class="tcard-sep"></div>
          <div class="tcard-feats">
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">L'Appel du SAHARA</div>
              <div class="feat-desc">Une semaine à Djanet, entre roche rouge et sable d'or, Sahara algérien.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Le Silence</div>
              <div class="feat-desc">Apprendre à écouter ce que le vide a à nous dire.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">La Mue</div>
              <div class="feat-desc">Un accompagnement quotidien dans les dunes par les Sentinelles, sans artifice.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Accès aux 2 OASIS</div>
              <div class="feat-desc">Espace intime Nomades + accès privé entre Nomades Sahariens.</div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Une méditation par module</div>
              <div class="feat-desc"></div>
            </div></div>
            <div class="feat"><div class="feat-dot"></div><div class="feat-content">
              <div class="feat-title">Rythme quotidien des nomades</div>
              <div class="feat-desc">Exercice du jour.</div>
            </div></div>
          </div>
          <div class="tcard-sens">
            <span class="sens-label">Le Sens</span>
            <div class="sens-txt">Vivre l'expérience du sacré pour revenir transformé à jamais.</div>
          </div>
          <a href="{{ route('inscription.form', ['choix' => 'absolu']) }}" class="tcard-cta outline">Soumettre mon Appel →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<footer>
  <div class="ft-in">
    <span class="ft-brand">Renait-Sens</span>
    <span class="ft-tag">Le sahara ne t'a pas changé — il t'a révélé</span>
  </div>
  <p class="ft-copy">© Renait-Sens · Tassili n'Ajjer · Algérie</p>
</footer>

<script>
// ══ MODE ══
let currentMode = 'night';
function setMode(m){
  currentMode = m;
  document.documentElement.setAttribute('data-mode', m);
  document.querySelectorAll('.msw').forEach(b => b.classList.toggle('on', b.dataset.m === m));
}
(function(){
  const h = new Date().getHours();
  if(h>=5&&h<9) setMode('dawn');
  else if(h>=9&&h<19) setMode('noon');
  else setMode('night');
})();

// ══ NAV SCROLL ══
const navEl = document.getElementById('nav');
window.addEventListener('scroll', () => navEl.classList.toggle('scrolled', window.scrollY>60));

// ══ BURGER ══
let mOpen = false;
function toggleMenu(){
  mOpen=!mOpen;
  document.getElementById('burger').classList.toggle('open', mOpen);
  document.getElementById('mob-nav').classList.toggle('open', mOpen);
  document.body.style.overflow = mOpen ? 'hidden' : '';
}
function closeMenu(){
  mOpen=false;
  document.getElementById('burger').classList.remove('open');
  document.getElementById('mob-nav').classList.remove('open');
  document.body.style.overflow='';
}

// ══ CURSEUR + FLAMME ══
const cur   = document.getElementById('cur');
const curR  = document.getElementById('cur-r');

// Canvas plein écran fixed — les coordonnées = coordonnées écran directement
const flameCanvas = document.getElementById('flame-cursor');
const fctx = flameCanvas.getContext('2d');
function resizeFlame(){
  flameCanvas.width  = window.innerWidth;
  flameCanvas.height = window.innerHeight;
}
resizeFlame();
window.addEventListener('resize', resizeFlame);

let mx=0, my=0, rx=0, ry=0;
let hoveredTier = null;
let flameParticles = [];

function ifac(tier){ return tier==='or'?1.8:tier==='argent'?1.1:.65; }

function spawnFlame(x, y, tier){
  const f = ifac(tier);
  const count = tier==='or'?6:tier==='argent'?3:2;
  for(let i=0;i<count;i++){
    flameParticles.push({
      x: x + (Math.random()-.5)*14*f,
      y: y,                            // ← exactement sur le curseur
      vx:(Math.random()-.5)*1.8*f,
      vy:-(1.2+Math.random()*2.5)*f,  // monte vers le haut
      r: 3+Math.random()*7*f,
      life:1,
      decay:.022+Math.random()*.018,
      heat:Math.random()*.5+(tier==='or'?.5:tier==='argent'?.3:.15),
    });
  }
}

function flameColor(heat,alpha){
  if(heat>.85) return `rgba(255,252,220,${alpha})`;
  if(heat>.7){const t=(heat-.7)/.15;return `rgba(255,${Math.round(200+55*t)},${Math.round(50*t)},${alpha})`;}
  if(heat>.5){const t=(heat-.5)/.2;return `rgba(255,${Math.round(80+120*t)},0,${alpha})`;}
  if(heat>.3){const t=(heat-.3)/.2;return `rgba(${Math.round(180+75*t)},${Math.round(40*t)},0,${alpha})`;}
  return `rgba(80,10,0,${alpha*.4})`;
}

let frameN=0;
function drawFlame(){
  frameN++;
  fctx.clearRect(0,0,flameCanvas.width,flameCanvas.height);

  if(hoveredTier && mx>0){
    if(frameN%2===0) spawnFlame(mx, my, hoveredTier);

    fctx.globalCompositeOperation='screen';
    flameParticles=flameParticles.filter(p=>p.life>0);
    flameParticles.forEach(p=>{
      p.x += p.vx + Math.sin(frameN*.09+p.x*.04)*1.1;
      p.y += p.vy;
      p.vy -= .045;   // accélération vers le haut
      p.vx *= .97;
      p.r  *= .976;
      p.life -= p.decay;
      const heat  = p.heat*(1-(1-p.life)*.8);
      const alpha = Math.max(0,p.life)*.88;
      if(alpha<.02||p.r<.5) return;

      const g1=fctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r*2.5);
      g1.addColorStop(0,flameColor(heat,alpha*.65));
      g1.addColorStop(.4,flameColor(heat,alpha*.2));
      g1.addColorStop(1,flameColor(heat,0));
      fctx.beginPath();fctx.arc(p.x,p.y,p.r*2.5,0,Math.PI*2);
      fctx.fillStyle=g1;fctx.fill();

      const g2=fctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r);
      g2.addColorStop(0,flameColor(Math.min(1,heat+.2),alpha));
      g2.addColorStop(.5,flameColor(heat,alpha*.8));
      g2.addColorStop(1,flameColor(Math.max(0,heat-.3),0));
      fctx.beginPath();fctx.arc(p.x,p.y,p.r,0,Math.PI*2);
      fctx.fillStyle=g2;fctx.fill();
    });
    fctx.globalCompositeOperation='source-over';

    // Halo braise exactement sur mx,my
    const hc=hoveredTier==='or'?'rgba(255,200,60,.35)':hoveredTier==='argent'?'rgba(180,210,255,.22)':'rgba(255,140,40,.18)';
    const cg=fctx.createRadialGradient(mx,my,0,mx,my,30);
    cg.addColorStop(0,hc);cg.addColorStop(1,'rgba(0,0,0,0)');
    fctx.beginPath();fctx.arc(mx,my,30,0,Math.PI*2);
    fctx.fillStyle=cg;fctx.fill();
  } else {
    flameParticles=[];
  }
  requestAnimationFrame(drawFlame);
}
drawFlame();

document.addEventListener('mousemove', e=>{
  mx=e.clientX; my=e.clientY;
  cur.style.left=mx+'px'; cur.style.top=my+'px';
});

(function ac(){
  rx+=(mx-rx)*.12; ry+=(my-ry)*.12;
  curR.style.left=rx+'px'; curR.style.top=ry+'px';
  requestAnimationFrame(ac);
})();

// Détecter hover sur les cards
document.querySelectorAll('.tcard').forEach(card=>{
  const tier = card.dataset.tier;
  card.addEventListener('mouseenter', ()=>{
    hoveredTier = tier;
    flameCanvas.style.display='block';
  });
  card.addEventListener('mouseleave', ()=>{
    hoveredTier = null;
    flameCanvas.style.display='none';
    flameParticles=[];
  });
});

// ══ REVEAL ══
const io=new IntersectionObserver(es=>es.forEach(e=>{
  if(e.isIntersecting){e.target.classList.add('v');io.unobserve(e.target);}
}),{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
</body>
</html>