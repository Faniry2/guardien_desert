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

/* ══ CURSEUR ══ */
.cur{position:fixed;width:7px;height:7px;background:var(--acc);border-radius:50%;
  pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:width .3s,height .3s}
.cur-r{position:fixed;width:28px;height:28px;border:1px solid rgba(212,98,42,.35);
  border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%)}

/* ══ GRAIN ══ */
.grain{position:fixed;inset:0;z-index:1;pointer-events:none;opacity:.028;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px}

/* scrollbar */
::-webkit-scrollbar{width:3px}
::-webkit-scrollbar-track{background:var(--body-bg)}
::-webkit-scrollbar-thumb{background:linear-gradient(180deg,#8B6530,#C9A96E,#8B6530)}
::selection{background:rgba(201,169,110,.2);color:#E8C97A}

/* ══ MODE SWITCHER ══ */
.msw-wrap{
  position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
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

/* ══ HERO ══ */
#hero{
  position:relative;height:88vh;min-height:560px;
  display:flex;align-items:center;justify-content:center;overflow:hidden;
}
.hero-sky{position:absolute;inset:0;
  background:linear-gradient(180deg,var(--sky-a) 0%,var(--sky-b) 50%,var(--sky-c) 100%);
  transition:background 2s}
.hero-overlay{position:absolute;inset:0;z-index:2;
  background:rgba(3,3,15,.58);transition:background 2s}
[data-mode="dawn"]  .hero-overlay{background:rgba(22,11,6,.48)}
[data-mode="noon"]  .hero-overlay{background:rgba(10,30,50,.32)}
#hero-canvas{position:absolute;inset:0;width:100%;height:100%;z-index:3;pointer-events:none}
.hero-dunes{position:absolute;bottom:0;left:0;width:100%;height:42%;z-index:4;pointer-events:none}
.hero-dunes svg{width:100%;height:100%}
.hero-content{position:relative;z-index:5;text-align:center;padding:0 2rem;max-width:860px}
.h-eyebrow{font-family:'Philosopher',serif;font-size:.7rem;letter-spacing:.6em;
  text-transform:uppercase;color:rgba(244,200,128,.78);margin-bottom:1.8rem;
  animation:up 1.6s .3s both}
.h-title{font-family:'Cinzel',serif;font-size:clamp(2.4rem,6vw,5.2rem);
  font-weight:400;line-height:1.08;color:#fff;
  text-shadow:0 2px 40px rgba(0,0,0,.5);margin-bottom:1rem;animation:up 1.6s .5s both}
.h-title em{display:block;font-family:'Cormorant Garamond',serif;font-style:italic;
  font-weight:300;font-size:.52em;color:rgba(244,200,128,.85);letter-spacing:.18em;margin-top:.5rem}
.h-div{display:flex;align-items:center;gap:1.3rem;justify-content:center;
  margin:2rem auto;animation:fadein 1.8s 1s both}
.h-div span{width:50px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,169,110,.55),transparent)}
.h-div em{color:rgba(244,200,128,.82);font-style:normal;font-size:1rem}
.h-sub{font-size:clamp(1rem,2.2vw,1.3rem);font-style:italic;font-weight:300;
  color:rgba(255,255,255,.82);line-height:1.8;max-width:540px;margin:0 auto;
  animation:up 1.8s .8s both}
.hero-scroll{position:absolute;bottom:2.5rem;left:50%;transform:translateX(-50%);
  z-index:5;display:flex;flex-direction:column;align-items:center;gap:.5rem;
  animation:fadein 2s 2s both;color:rgba(255,255,255,.42);
  font-family:'Philosopher',serif;font-size:.62rem;letter-spacing:.35em;text-transform:uppercase}
.scroll-line{width:1px;height:42px;
  background:linear-gradient(180deg,rgba(201,169,110,.65),transparent);
  animation:scroll-p 2.5s ease-in-out infinite}

/* ══ SECTION TRAVERSÉES ══ */
.section{padding:8rem 3rem;position:relative;z-index:10;background:var(--body-bg);transition:background 1.8s}
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
  cursor:default;
}
.tcard:hover{
  transform:translateY(-4px);
  box-shadow:0 20px 60px rgba(0,0,0,.3);
}

/* Badge */
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

/* ══ MÉTAL BADGE + BORDURE PAR CARD ══ */

/* BRONZE — Regard */
.tcard:nth-child(1) {
  border-color:rgba(205,133,63,.3) !important;
}
.tcard:nth-child(1):hover {
  border-color:rgba(205,133,63,.6) !important;
  box-shadow:0 20px 60px rgba(160,82,45,.3) !important;
}
.metal-badge {
  display:inline-flex;align-items:center;gap:.4rem;
  font-family:'Cinzel',serif;font-size:.58rem;letter-spacing:.5em;
  text-transform:uppercase;padding:.3rem 1rem;border-radius:2px;
  margin-bottom:1rem;
}
.metal-badge.bronze {
  color:#FFB347;
  background:linear-gradient(135deg,rgba(90,40,10,.5),rgba(160,82,45,.3));
  border:1px solid rgba(205,133,63,.4);
  text-shadow:0 0 12px rgba(255,160,50,.7);
}
.metal-badge.argent {
  color:#D8EAF5;
  background:linear-gradient(135deg,rgba(20,30,50,.5),rgba(60,90,130,.3));
  border:1px solid rgba(120,160,210,.35);
  text-shadow:0 0 12px rgba(160,210,245,.6);
}
.metal-badge.or {
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

/* ARGENT — Présence */
.tcard:nth-child(2) {
  border-color:rgba(120,160,210,.25) !important;
}
.tcard:nth-child(2):hover {
  border-color:rgba(120,160,210,.55) !important;
  box-shadow:0 20px 60px rgba(80,120,180,.25) !important;
}

/* OR — Absolu */
.tcard:nth-child(3) {
  border-color:rgba(255,215,0,.28) !important;
}
.tcard:nth-child(3):hover {
  border-color:rgba(255,215,0,.6) !important;
  box-shadow:0 20px 60px rgba(218,165,32,.35),0 0 80px rgba(255,215,0,.1) !important;
}

/* Sweep reflet hover */
.tcard::after {
  content:'';position:absolute;inset:0;
  background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,.07) 45%,rgba(255,255,255,.14) 50%,rgba(255,255,255,.07) 55%,transparent 70%);
  opacity:0;transform:translateX(-120%);
  pointer-events:none;z-index:5;
}
.tcard:hover::after {
  opacity:1;transform:translateX(120%);
  transition:transform .7s cubic-bezier(.25,.46,.45,.94),opacity .05s;
}

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
.price-val{font-family:'Cinzel',serif;font-size:1.6rem;font-weight:400;
  letter-spacing:.03em;transition:color 1.4s}
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

/* Le Sens */
.tcard-sens{padding:.9rem 1.2rem;border-left:2px solid var(--acc);
  margin-bottom:2rem;background:rgba(212,98,42,.05);transition:border-color 1.4s}
.sens-label{font-family:'Philosopher',serif;font-size:.6rem;letter-spacing:.35em;
  text-transform:uppercase;color:var(--acc);opacity:.8;display:block;margin-bottom:.35rem}
.sens-txt{font-size:.92rem;font-style:italic;line-height:1.6;transition:color 1.4s}
[data-mode="night"] .sens-txt{color:#E0C080}
[data-mode="dawn"]  .sens-txt,
[data-mode="noon"]  .sens-txt{color:#3D1C00}

/* CTA boutons */
.tcard-cta{
  display:block;width:100%;padding:.92rem;text-align:center;
  font-family:'Philosopher',serif;font-size:.8rem;letter-spacing:.22em;text-transform:uppercase;
  text-decoration:none;border:none;cursor:pointer;
  position:relative;overflow:hidden;transition:all .35s;
}
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
.bg-bronze{
  background:url('data:image/jpeg;base64,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') center/cover no-repeat;
}
.bg-argent{
  background:url('data:image/jpeg;base64,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') center/cover no-repeat;
}
.bg-or{
  background:url('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAA0JCgsKCA0LCgsODg0PEyAVExISEyccHhcgLikxMC4pLSwzOko+MzZGNywtQFdBRkxOUlNSMj5aYVpQYEpRUk//2wBDAQ4ODhMREyYVFSZPNS01T09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0//wAARCAGQAOEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwDIK8mm7amK03Fecd5EVppFTEU0j1piISKaRUxX0puKdxWIiKaVqUimkU0xERWmketSkU0iqTFYiwO1IRUhFJimIjxTSKkIppFMQw0hWnkelJTER4pCKeRTcU7iGUYp+BTSKdwG0lOIpMVQCYFN206ikIYetFPIpuKYCUUUUxBRRRSuM6sr1ppWpyvNM21wHWQlaaRipiuaaVp3FYgIppFTlaYRVXFYhK00rUxFNK1QiEimkVKRTSKdySIikIqQikIppgQkUhFSkUzFUmSRkUmM1IRTCKYhhGKQ0+k2+lMRGRikp5FIRTAZjNIRTqMU7iGEc0hp5FNIp3AbRTscUlMBuKMUtFArCYFFLRQKx2RWmFasFaaVzXmnaVytNIqcrTCtVcRAVphX1qwVphX1qkxEBWmEVYK0wrTQiAimkVMRTCKpMRERTSKlIppFUSREUwipiKaRTAhK03FSkUhFNMViIimmpCMUhFVcRGRmmkVIVpCtMRHik20/FIRigQwjFJin4zSFaYhhFNIqTBpKdwGYpMU8ikxTuIbiinYFFFxnclaaV5qcrTSteZc7CuVppWrBWmFapMRXK0wrVkrTSntTuFiqVphWrLIfSmFDjpVXFYrFaaVqwUphWquKxXK00ipyKYy1VySErTSKmK0wimmIhIppFTYppFMRCRTSvpUpFIRVIRDikIqQj1ppWncRGRSYqQikxTuIjI5pMU+kxTuIZSbaeRSYpgR7TRin0YpisMxRT6KAsd8y00pVnZSiNT2ryrnaUiho8ontV4pj+DNNK5P3cU+YRSMWOopCAKvbB6VDJFk/dwKEwKTVERmrbx+hqEx+prRMRVYUxlq0yiomUVaYrFdlqMrVkimEVQiuVphWp2WmFaaYiEimEVMy00rVE2ISKaRUpFNK07iIitNIqUikIp3EQkZppU1MVpCtUBDikxUhWkK0EkRFJipMUhFO4EZFJipMUmKdxDMUU/FFO4HopFJipNpo2V5R2DAPVjTy2BgUbD6U0gigQwswzk1E8mR3NSMtRMoFNDIXyaiK1OwphWrQiuVpjLVgrTCtUmIrFaYy1YK0xl9qq4iuRUZWrBX2phWqTFYgK0wrU5WmlaoRAy0wip2XmmEU7isQkU0ipitNK+1UIhIppFTFaaVpiIiKaRUpWkxTuIixTStSlaTFMRFikqQikxTAjwKKk20UAekYxSVJgUhKj+GvMOgaHxTJGHpTjz2pjCgCFxUZFTkUwrTGQFaYVqcimstMCuRTCtWCtMK1SYFcrTCtWCtMK+1UmIrlaYVqwVphWquIrlajK1ZK0wrVJhYrlaYVqwyVGVp3JICtNIqcrTStUKxAVppFTbaaVp3FYhK0m2pStNIp3AiK00ipiKaVpisRYpNtSbaQimIZtNFPxRT1Eei00ipcUhFeadBERTSKlIppWgCEimlamIxTSKBkJFRlanK800imBARTStTleKYVpgQFaYVqwVphWmmIrlaYVqwVphWqTArstMK8VYK0wrVXArlaaVqwVphWqTFYrlKYVqwVppWmmIrFaaVqwUphWquKxBimlanK03bTuIgK00irBWm7adwsQFaTbUxWkKU7isQ7feipdlFFwsehYFIVFSUmK4TS5ERTTUpFNK0gIsU0r6VKVxTcUDIiKaVqYjNMIpjIStNIqbFIVoAhIphWpiuKQimBXK0wrVgrTStO4isy4ppWrBWmlaaYFYrTCtWSlMK1SYFcrmmlKsFaaVp3EVitNKVZIxTSM07gVjHSFBUxU00qaq4iAqPSkIAHAqYpmmlDVXEQmmkVKVpCtAEOKKl20UxHeYpMU7FFclhjcU0inGikO5GRSbakIpDSGQ4pCM1KRTSKBkRWmlamxTcUwIsU0rUxWmlaAICtIVqYrSFaYFcr6U0rVgpSFKAKxWmFParRjFJsWncCoV9KaUq4USmlU9KdwKZjppSrmI/ekOwdKdwKRQelNK1cJX2/KmMRTTAq7D6fpSeTn2+tWCRTCRTuxEYts/xCmm256inlqQk09QG/Z/daKXNFGoHaGP2NNMbelSZb1oy3979KOVGF2RGNvSmFD6GrBZv7wpNz+oqXFFKTK5U+hpMVYLP6A03e3dRU8qGpEBFIRU+/PVR+VNJU/wANJxKTIMUhXipiF9KTAqbDuZup6hBpluJbgMdxwqqMlj6Vys/jK6ErCK1iCBjt35zj35rtbuOB7aRbkIYdpLb+gHrXmV2EkundMEE9R3rWkk90Poa9r4zmWRvttujJjjyuDn8alk8axbv3dkxX1aTB/lXNNEpHIpnlqTjHFb+zh2FqdT/wmkOB/oT57/vB/hVu38U6dLA8kxaFlONh+Yt9MVxnlj0/WjyQPWl7KAanaDxPpZjZjJICP4SnJ+lNg8S6ZPkGV4cf89Fxn8s1xjQA+taPh+zgl1iFJo0dcE7X6EgcUnSilcLs7dSHQMpyGGQR3FIRU23FNIrnKIStNK1MVpNtO4FcrTSKnK00rTTAg200rU5WkKHsKdwsQFaaVqfYf7po8o+houBBtoqbyj6Gii4HWZNGaXFJiq1OcaTSZp2BRipGNyfWkye9OIxSUhiZpp607FJigBDSU4ikxSKOX8XRSDbKJpRH5TAxg/JkEf41xuAe1dr4yuGjit4QqlH3GQk4IHT/AD9K4zAJIH4VtT2L6DNoxSeWOuCPxqWNC5AUEknCgdzXWaX4RVohJqbMGPIiQ4x9TVuSiJ+ZxbLhtuW6dc0pHoxrt9Q8GWkoL2M8kEmOA53L/iK46/tLiwu2trxNsg5BB4YeoNEZqQIhIPqaks7ie2vEkgI8wHAO0HrxTeec9R+tMU7LhSDjBBB/GqtcTPUPK4560nlCrAGQCCMHnPrSbfeuMq5X8tR2pCqe1TlfemlM96QEBCe1IdvpU5QdzTCi0xkJI9KaW9qnKD0ppQUAQZPpSEmpig9KQotAEPze1FS7V9KKYzoMUYNLSk461qzlGEGkwacKXFIYzBop1BoYIaelJ+FOoqbFDKTqafTXZY0MjnCqMk+gpCPLdauDNq12dzFTM2NxzjBI49qp/wAIGeTxS6jthvJsMZAZGwVHJ5pqncVIIx15NdStZGp1Pg7T1munvJF+WDAQf7R7/gK7Oua8G3lt9jazDHzy7OQFOMcd66Wuee5L3Cs7WtPivbM70BdBkHHOO9aNIcBTnAHfNQwi7M8v1W2+zXKkDCuMj+RqlEsb3KLO5jjz8zBdxA+ldD4qEX7lEdTIpbcueQDjFc4wOWOOMV0UneKuXNanp2m31rqFr5tnIXRTsORggj1FWStc34EyNNufTzh/6DXTGsJq0rCQzBpMU/tTakY2mlacaKBkZHNNxUhFNIoEMpCKeaaRQMZtop2PeigdjcPNB5pnmJ/eFIZU/vCtbo57D6M1EZ09/wAqTz1Pr+VLmQcrJc0lReeue9OE0f8AepXQcrH0Zpu8EcMDSUXGOJoI3IR6jFNqC/laKwuJFJDLGSD+FIaPMdQgeO+kVwByTwfwqJVAXjirFwvzqHzkxhjn1Iyf51C2BjB4rdbGvU7TwTGq6XO4HzNLhj64Ax/OuirnvBf/ACCJSOhmOD+AroM1jLcze4pNMkVZI2R1BVhgg0uR60m4eopAkcH4qgSKaDaEBIbO0e9c+n32+grovFjky27ED+NQPx61zin53/CtqK9w0m9Tq/BeqRqz6W6kOztIjAcHjkH8q641514WeKHxDCZdxZ8qm3oCQetehFh6ioqxtIhCk9qSkyPWkzWZQGikJHrSFgOpFFhi5pppDIvqPzppdf7w/OnYQ6mmk3qf4h+dJuHqKBi0U3cKKVgLXmr60eavrWUbhj3/AFpPNPvUlWNbzB60hkHrWUJCeganF3UfMcfXigOU0vMFHmiswSv2fP05p3nSAZwx/CgOU0PMFAmx0JFZouz6D86UXQ75FMOU0vPP94/nVTUbuNLR45ZQvmqUUE9Se1Q/al9awPEFy0l9aRIflHzY98//AFqcVd2C1jMuZzPM0oXAYADn0GP6VCCRxjkU2M4jHPrSb/mNdKRLZ23h+5zotsgCAxpsYL0BFaPnN61y/h+dUtpd2eXH8q1ftIbhWVfrXPO92UkrGiZW9aQyt71RAmYZEikU7bN7GpuPlMPxZGyvbbiD98/qK5pT87mum8YO3nQISMBScZ/z6VyoOVb3NdND4DOo7Mt6ZIBrNqzNtCyLz17133nf5FebI5S4STONpB/WurGu6cYjI0jnBxtxg/lTqxelhQa6m75p9D+NJ5p7kfnXPwa/YzTLGPMj3HGW4A+pzWvEbeSNZFuQyN0KHINYSTjuaKz2JzMB1OfpURuD2GKiuBKRi0I9945/wqkYdSJ+65+mKcWgasaH2hj1GaUTZ74+oqrHDqLLtYRgf7RA/lVyCymA+d0A9EBP86Tml1GkNMpHuPWm+aSeAR9RU10EtbdpViMzKPu5xWC+s3LNlEiQegTP86INz2B2W5sbj6j8qKxv7Zu/WP8A74oq+SYuaJpGX2H50ece386hEEx58sgercfzpjYQ4Mi5/wBnmosgLi3cg6Mw/Gp0vUOPMBP1ANZqyoPvEn/gP/16mjaCQgDzCfQCk0ho0xfwovyAZ9hiqk908x5bA9AaelmrdAw/3qgmWWK4ENtbGYj7z8Kq/ie9SrDsNAJPAJ+lSCKU9FarICKAWkOQOVXpXK6q1wbyVyThjhQrE4A7VUPedgehr3N2ls2x2y/90c1n3029ZJgoLowVCVzx1yP1/Os5JGADSx7jjAyaebh5IwjLtUDovFaqFmRzFXf2HH0FRhz6fjV2G3jlyPm+nepxppb7in8eK154oz5WyTQblEkljmYBCu4Zx1rbW+tQ22NoWP4Vz50yX+EqaBpl3nMcLPx2FZSjGTvc0i5JWsdFLcFxhmXHop/woglH2hFDHGQSd3AFY8Fhq/SO2dR74UVoQ6bqxP7yS3X6uT/KsZRS6otSXYg8TqlxcQr5qKqqdx3fN+Arn5LFg3+jrK6diUxXax6Zdj70ttn1CMTStoskrZlu8j0EfT8zThV5FYmUVI4J4GUnchB9xURjB46fSu/u7PTNNhEl7cSnP3VBALfQAViXWsWvK2emQgf3p/nP5dK6IVnLZGLgl1OZ8kZ+9WxpOqPp0LRNGJVzlexU96bNqF1JwZAg/uxoqD9BVVnLHLEk+prVrnVpIS913RrweI52mf7QFSMj5di5IrSttX02RQ096wP91lIrlMA9qaV9KylQg/IpVJI9DW5tVYJG6FiMgBgTike5J6A159G7wvujdkb1U4qymoThv3rvIvoXI/UVi8L2Zoqy6o7F3Zz901A9rFMfngj+p61lWniC1iXY1rMPpJu/nVxPEFgx4JjPq6H+lZ+zqR2RqpwZP/Zlr/zyT9aKb/bll/z8x/kf8KKn955heHkK8cSL++u8D0PJqnLd2MeQivKfrtFZZLs2CdzenemsGzjFdKh3Zi5FxtQIbMcES/UFv50v9q3uNqyhB6IoFUSCOowaBmq5ULmZoprN8nSUH6qKf/bNw3+sVW+mRWZTlRmOFBP0FJwj2GpM0jqYcYKsnuOaWESzj9zOuM9XQk1BDpV7LjEJUer/AC1uWdkLWAIZFLdSQO9ZT5YrQuLb3K/9mJJ87bC2Mc5x+VNi0Vcku8TfRK0z5SkAksxHA9aa0kzIPICIe4IzWXPIuyIU0qJBkuF+iAVMsEC8AM314p+yQrk4zjv0zSNLbxj99cRr+Oam8mA5EiTJSJQT3xUylz90flWdLr2l25wGkkI/up/jVWTxbCP9TZyN/vOBTVKb6EuaN4REn52/AVKqBegrkZfFt22fKt4E9zljVCfX9TnyGumQHtGAv8qtYabIdRHeSyRW6b55UjX1dgKybrxNp8GRCXuGH9wYH5muJkleVt0js7erHJpu6to4aPVkuZpapqsupzrJKioqjCqOw+veqJwaj3Ubq6FFJWRF7isKbTt1BwaoRGTSZNOZPSm9KaJFz60mQaaTTadgH4HY0mPcU3NGfegQ7b9KKbu96KANuSzaM/vAsZ/66qf60qSGLhbmIj0LCsQ9aM1l7Puac50kFxG5w6Qk+2KtpHbN1gQ/hXI5p6yunKOy/Q4qHR7MtVO6OzSOIf6uCEf8A5/WrcXnNwIuM8YXGK42DWNQt+I7p8ejAN/Or8Pim+X/AFqQyj3BX+VZSoT6FKqjqRHMcbwg+rUp8tR8z8/7NYUPiqBsCe0ZfdW3fzq7FrNhPjbKin0dStYulNbotTTLT3MUY+VSfqapzamyg7VA/CrO9JBlURx6o2aryQwvwRtPuKEl1GZdzqsp7ms2a6kk6mtyXTVf7hBrPn0qRckKfwrohKCM5KRknJpDViW2kjJypqEr6it00zJoZSUpGKQ0xBRnPSkNJyKYh1GRTaKLBcdkUZFNoHWgB+aXIPUUlFACGNTTo7YSSBN4XPGSKF6VasreWaZSi8A8ntSlKyKUbslPhy7K7o5oHB9yP6VG2gaivSONvpIK6hMhACelBbFcf1mZ0ewicr/Yepf8+4/77X/Giup3+9FP6zMPq8ThD1NAppPJozXaco7NGaTNFO4Ds80oNMpckUAPzShsUwNSg5pWAnjnkjOUdlPsavw61dxgBpCw9+ayhSg1LgnuUpNHRQ6yr/fjTPqOKvRalC394fjmuSBx0qzDJnvWMqK6Gimzq/MtJh820/hUUumWcwO1gCax4XPrVyOQjvWPK47M03GT+H35MLq3tms6fTLqHO6FiPUDNbyTN61YSdh3pqtNbidNM41kIOCCD700rXbkpJ/rI0f/AHlBphsbGT79pD+C4q/rK6on2LOK2+1G2uybR9Nb/l3K/wC65FNGhad/ck/F6f1mJPsmcft+tAXngZrsxotkv3U/MZp406Nf9WQv/AaTxMRqicfHazvyI2C4ySRgVZt9OMrKGmRdwyO5Irpv7PbjEg/HNINPlA+9GfwxUPEX2LVJGZDpNtFy5Mh9+lXI0Eb/ACuAuMBQBgVMbCf0Q/8AAqb9juF6J/49WTnzbs0SS2I50ZxmOQo+MA9cfhQrMFAcgn1xinm2uP8Anm34VGYpFPzIw/CloO4u4+1FJtb/ACKKLDucSeppKQ9aWvVPOFzRupKKGMdmlpgGaeBikAUopKcKAFHSlBopRSGKOlPU4IIpopRSYzTtJBIuO9aCJWLaErMMd66OBNyg1yVdGdENRESp1WnLHUqx1ztmthqqakUU5UqRUqWwGAelPC08Jinbam4DQKcFpRRSuAmKWjNGKQBmijFFABij6UUhNMA5/uiim596KAueXjrS0BTmpUt5GGQvHvXtNo4LEQGaUIQeDxUrRsmAwxTQGz0FFwsIBS4NPxjtQAfSi4xoFKBTwtOCHHSlcLDAKcBTgh7c1YhsbmY/u4HP4VLkluUkysBTgK2bfQLh8GZljH5mta10W1gwxBdvVqxlXijSNNsw9M0+WaQOVIUetdLFb7FAqwqKowoAFOrknUc2bRXKRiOnhRS0oORkVk2MUKKXFJk5paQwoooyKQBSgU3d7Uhb1p2C4+kyPWmFqTdRYQ/d6CkLUzPrUVxcRW0LSzMFRRkmml2GTbqjmnigTfNIka+rHFcrf+JbiQlbMCJP73Vj/hWJNPLM++aRpG9WOa6oYWT+LQxlVS2O5/tzTP8An8T8jRXBZorT6pHuR7ZmqltDuxtLN1yOlR3EN0HHlhiD0GKff3BjYiMhVqTSbxnkwxxn1qrytzDsr8o62snuUxK2xx1DKQasjQ4/4rjH4VdlnWKPcxBqmNTBcKvc96y55vY05Yrccuhwn/lux+gFSrodqDzJIfyrQiXfH16j+Gnx24RcEs3u3Ws3Vl3K5I9jPOj2wQmDl+285FJa6dNHLm6EEkWMBRnj9K1Y4kjQKq4A6CnlFIII4qXUY+VFeHyAwEVvGmDzlcGrBLOpUblBHBU4NLHGsaBUBwPxpx3lTtAB7ZqG7jBA6qBndgdWPNOG7uQKRAwX52GfanAgeppAIyBgASfXg4p+ARjHFJuFIXoAfS8etRF/emlqLCsT7hTd/vUW6kzRYdiUvSF6ZmgA0gHFzSbiaULTJJYYhmWVE+pxTAdk0v41nz65pkHW4Dn0QZrMufFaYxa25J/vSHH6VcaM5bIl1IrqdH05Nc14ivYJytskudpy2Dxmse81i+u8iWYqh/hTgVS3GuqlhnF8zZjOsmrIleJl5HI9RUJpwkI6GlLBuo5rqMWMop2F9aKYrHXKA/DqhJ/GnC1tgdxiTPqBimpZkHmRQPYZqcW0OQX+Yr07YrzW/M7itdWwngKxHaf4TnNZKaNqEUocCN/bdXSKI0+6oGaf5mKcajjohOKkQW32koFkg8rA/vAipBaSNnzJyOcjaSMU/wA2jzDWZQ63t1gBzLJIT3c1NvAquX460m+luBa8ymmSq+8ml3Z+lFgJ99G6oQec0A4BAJ560WAl3cZpd3aouCACcKO1DzRg5aRQB70WAlzyR0xRkbc5qjLqlhF9+5TPoOaqSeJLGPiNZH+gqlTk9kS5RW7Nse/FL8o4GSa5iTxV18q2/M1Tm8S3z5EeyMewya0WHqPoS60Udm0iRrucqo9SayL7xHa2+Vh/ev8A7PSuRnvLm5OZpnf6moc1tDCJfEzKVfsal3rt/ckjzTGn91OKzWldzl2Zj6k5pmaM11RhGOyMXJvcduOKSm5ozVCHZpKTNGaAFzS5pmaM0WFcfmim7qKLBc7jzKPMqqZgoLHp7CoVvgzFURvqRXmclzvujQMqqMk1lS69Gl0IwuYwcM1T3ANxBsDhSe/SqkekWitktvPua0goL4iJOX2TZinSZA8bgqe+ac88Ua7ncAepqlFGsQ2qFCjsBUE9382Egdse3FRyXehd7LU0ormOfJicMO5FPZwi5z1rLhuJP40SFPrVmO6gX7oZj6k0nCwKVyV5br/lnbbvctgVKkkwx52xT/dXk1GJ2KEpkn0NYt1rF3HKQqKuO9OMHPRClJR1Z06tkfc49TWPqWt+Q7QwqGI/iz0rFl1i+lXaZsA+lUSxY5J5reGGs7yMpVr/AAlufULyUnM7YPYcVUaR3++7H6mg0ldKil0MG29xDxQKQikqhC5ozSUlAri5ozSUUBcWikzRQFxc0lJRmgQuaM02lzTAM0ZpKKAHZoptFAHSxF95LMSD0FJdFscTeX+FHnLGTyWqvPIHyXRQPeuFLU7HsQeT5r4a9H51o28cMC4icyse+azBJYq3zKT9K1LK6tz8sMOPwqql7EwtcvQl9nzKAPrUD2ZnPy3BHtmm3C7sGSbaD2p8PkxrkPmsdtUa76EQ0chgzzDHuanH2C2/1twv4Vn6hJ53yiYj2BrFmgeM5IyPWtY03NaszlPk2R08ut2ESFIPmJ74rLcLc5ZSGzzisbJpySMhyrEfSto0FHYydVvcsTQMh6HFQ1bivgw2zrketOktklG+3YH2q1JrRkuKexSzRSuhQ4YEU2rJA0lLSGgApKKQGgkWk/GkzRQAUUUlMBaKTNJmgLi0ZpM0ZoELRmkzRQAUUZooA//Z') center/cover no-repeat;
}

/* ══ FOOTER ══ */
footer{
  background:var(--body-bg2);padding:3rem 3rem 2rem;
  border-top:1px solid var(--feat-border);
  position:relative;z-index:10;transition:background 1.8s;
}
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

/* ══ KEYFRAMES ══ */
@keyframes up{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}
@keyframes fadein{from{opacity:0}to{opacity:1}}
@keyframes scroll-p{0%{transform:scaleY(0);transform-origin:top;opacity:1}50%{transform:scaleY(1);opacity:1}100%{transform:scaleY(1);transform-origin:bottom;opacity:0}}

/* ══ RESPONSIVE ══ */
@media(max-width:1000px){.traversees-wrap{grid-template-columns:1fr}.tcard-visual{height:180px}}
@media(max-width:820px){
  #nav{padding:1.2rem 1.5rem}
  nav ul,#nav-cta-d{display:none}
  .burger{display:flex}
  .section{padding:5rem 1.5rem}
}

/* ══ CARDS MÉTAL OPAQUE ══ */

/* BRONZE — Regard */
.tcard:nth-child(1) {
  background: linear-gradient(160deg,
    #1A0A02 0%, #4A2008 18%,
    #8B4513 32%, #CD853F 45%,
    #E8A040 50%,
    #CD853F 55%, #8B4513 68%,
    #4A2008 82%, #1A0A02 100%
  ) !important;
  border-color: rgba(205,133,63,.5) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,200,130,.2),
    inset 0 -1px 0 rgba(0,0,0,.4),
    0 0 35px rgba(160,82,45,.25) !important;
}
.tcard:nth-child(1) .tcard-body { background: transparent !important; }
.tcard:nth-child(1):hover {
  border-color: rgba(255,180,80,.7) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,210,140,.25),
    0 0 55px rgba(205,133,63,.45),
    0 20px 60px rgba(160,82,45,.35) !important;
  filter: brightness(1.06);
}

/* Textes bronze */
.tcard:nth-child(1) .tcard-name   { color: #FFF0D8 !important; text-shadow: 0 2px 8px rgba(0,0,0,.8) !important; }
.tcard:nth-child(1) .tcard-tagline { color: rgba(255,220,160,.82) !important; text-shadow: 0 1px 5px rgba(0,0,0,.7) !important; }
.tcard:nth-child(1) .price-label   { color: rgba(255,200,130,.7) !important; }
.tcard:nth-child(1) .price-val     { color: #FFF0D8 !important; text-shadow: 0 1px 6px rgba(0,0,0,.8) !important; }
.tcard:nth-child(1) .tcard-temps   { color: rgba(255,200,130,.72) !important; }
.tcard:nth-child(1) .tcard-sep    { background: rgba(255,180,80,.3) !important; }
.tcard:nth-child(1) .feat-dot     { background: #FFB347 !important; box-shadow: 0 0 6px rgba(255,160,50,.6); }
.tcard:nth-child(1) .feat-title   { color: #FFF0D8 !important; text-shadow: 0 1px 4px rgba(0,0,0,.7) !important; }
.tcard:nth-child(1) .feat-desc    { color: rgba(255,220,160,.78) !important; }
.tcard:nth-child(1) .sens-txt     { color: rgba(255,220,160,.85) !important; }
.tcard:nth-child(1) .tcard-sens   { border-color: rgba(255,160,50,.5) !important; background: rgba(0,0,0,.15) !important; }
.tcard:nth-child(1) .tcard-num    { color: rgba(255,200,130,.7) !important; }
.tcard:nth-child(1) .tcard-cta.outline {
  color: #FFF0D8 !important;
  border-color: rgba(255,180,80,.45) !important;
  background: rgba(0,0,0,.2) !important;
}
.tcard:nth-child(1) .tcard-cta.outline:hover {
  border-color: #FFB347 !important;
  color: #FFD4A0 !important;
  background: rgba(0,0,0,.3) !important;
}
.tcard:nth-child(1) .metal-badge.bronze {
  background: rgba(0,0,0,.25) !important;
  border-color: rgba(255,180,80,.45) !important;
}

/* ══════════════════════════════
   ARGENT — Présence
══════════════════════════════ */
.tcard:nth-child(2) {
  background: linear-gradient(160deg,
    #080A12 0%, #151E2E 18%,
    #2E3D56 32%, #6080A8 45%,
    #A0C0D8 50%,
    #6080A8 55%, #2E3D56 68%,
    #151E2E 82%, #080A12 100%
  ) !important;
  border-color: rgba(120,160,210,.45) !important;
  box-shadow:
    inset 0 1px 0 rgba(200,230,255,.18),
    inset 0 -1px 0 rgba(0,0,0,.4),
    0 0 35px rgba(80,120,180,.2) !important;
}
.tcard:nth-child(2) .tcard-body { background: transparent !important; }
.tcard:nth-child(2):hover {
  border-color: rgba(180,220,255,.65) !important;
  box-shadow:
    inset 0 1px 0 rgba(200,230,255,.22),
    0 0 55px rgba(100,160,220,.4),
    0 20px 60px rgba(60,100,160,.3) !important;
  filter: brightness(1.08);
}

/* Textes argent */
.tcard:nth-child(2) .tcard-name    { color: #F0F8FF !important; text-shadow: 0 2px 8px rgba(0,0,0,.8) !important; }
.tcard:nth-child(2) .tcard-tagline { color: rgba(180,220,245,.82) !important; text-shadow: 0 1px 5px rgba(0,0,0,.7) !important; }
.tcard:nth-child(2) .price-label   { color: rgba(180,220,245,.7) !important; }
.tcard:nth-child(2) .price-val     { color: #F0F8FF !important; text-shadow: 0 1px 6px rgba(0,0,0,.8) !important; }
.tcard:nth-child(2) .tcard-temps   { color: rgba(180,220,245,.72) !important; }
.tcard:nth-child(2) .tcard-sep     { background: rgba(120,160,210,.3) !important; }
.tcard:nth-child(2) .feat-dot      { background: #A0C0D8 !important; box-shadow: 0 0 6px rgba(160,210,245,.5); }
.tcard:nth-child(2) .feat-title    { color: #F0F8FF !important; text-shadow: 0 1px 4px rgba(0,0,0,.7) !important; }
.tcard:nth-child(2) .feat-desc     { color: rgba(180,220,245,.78) !important; }
.tcard:nth-child(2) .sens-txt      { color: rgba(180,220,245,.85) !important; }
.tcard:nth-child(2) .tcard-sens    { border-color: rgba(120,160,210,.5) !important; background: rgba(0,0,0,.15) !important; }
.tcard:nth-child(2) .tcard-num     { color: rgba(180,220,245,.7) !important; }
.tcard:nth-child(2) .badge         { color: #D8EAF5 !important; background: rgba(0,0,0,.25) !important; border-color: rgba(120,160,210,.35) !important; }
.tcard:nth-child(2) .tcard-cta.primary {
  background: linear-gradient(135deg, #3A5070, #6080A8) !important;
  box-shadow: 0 4px 20px rgba(80,120,180,.5) !important;
  color: #fff !important;
}
.tcard:nth-child(2) .tcard-cta.primary:hover {
  background: linear-gradient(135deg, #4A6080, #7090B8) !important;
  box-shadow: 0 8px 30px rgba(80,120,180,.6) !important;
}
.tcard:nth-child(2) .metal-badge.argent {
  background: rgba(0,0,0,.25) !important;
  border-color: rgba(120,160,210,.4) !important;
}

/* ══════════════════════════════
   OR — Absolu
══════════════════════════════ */
.tcard:nth-child(3) {
  background: linear-gradient(160deg,
    #0D0800 0%, #2A1800 15%,
    #6B4500 28%, #B8860B 40%,
    #DAA520 46%, #FFD700 50%,
    #DAA520 54%, #B8860B 60%,
    #6B4500 72%, #2A1800 85%,
    #0D0800 100%
  ) !important;
  border-color: rgba(255,215,0,.45) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,180,.22),
    inset 0 -1px 0 rgba(0,0,0,.5),
    0 0 45px rgba(218,165,32,.3),
    0 0 80px rgba(255,215,0,.08) !important;
}
.tcard:nth-child(3) .tcard-body { background: transparent !important; }
.tcard:nth-child(3):hover {
  border-color: rgba(255,215,0,.7) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,180,.28),
    0 0 65px rgba(218,165,32,.55),
    0 0 100px rgba(255,215,0,.18),
    0 20px 60px rgba(160,120,0,.4) !important;
  filter: brightness(1.08) saturate(1.1);
}

/* Textes or */
.tcard:nth-child(3) .tcard-name    { color: #FFFEF0 !important; text-shadow: 0 2px 8px rgba(0,0,0,.9) !important; }
.tcard:nth-child(3) .tcard-tagline { color: rgba(255,238,160,.85) !important; text-shadow: 0 1px 5px rgba(0,0,0,.8) !important; }
.tcard:nth-child(3) .price-label   { color: rgba(255,230,120,.72) !important; }
.tcard:nth-child(3) .price-demand  { color: #FFF8DC !important; text-shadow: 0 1px 6px rgba(0,0,0,.8) !important; }
.tcard:nth-child(3) .price-note    { color: rgba(255,220,100,.6) !important; }
.tcard:nth-child(3) .tcard-temps   { color: rgba(255,230,120,.72) !important; }
.tcard:nth-child(3) .tcard-sep     { background: rgba(255,215,0,.3) !important; box-shadow: 0 0 6px rgba(255,215,0,.2); }
.tcard:nth-child(3) .feat-dot      { background: #FFD700 !important; box-shadow: 0 0 8px rgba(255,215,0,.7); }
.tcard:nth-child(3) .feat-title    { color: #FFFEF0 !important; text-shadow: 0 1px 4px rgba(0,0,0,.8) !important; }
.tcard:nth-child(3) .feat-desc     { color: rgba(255,238,160,.78) !important; }
.tcard:nth-child(3) .sens-txt      { color: rgba(255,238,160,.88) !important; }
.tcard:nth-child(3) .tcard-sens    { border-color: rgba(255,215,0,.5) !important; background: rgba(0,0,0,.18) !important; }
.tcard:nth-child(3) .tcard-num     { color: rgba(255,215,0,.7) !important; }
.tcard:nth-child(3) .tcard-cta.outline {
  color: #FFF8DC !important;
  border-color: rgba(255,215,0,.45) !important;
  background: rgba(0,0,0,.22) !important;
}
.tcard:nth-child(3) .tcard-cta.outline:hover {
  border-color: #FFD700 !important;
  color: #FFD700 !important;
  background: rgba(0,0,0,.3) !important;
}
.tcard:nth-child(3) .metal-badge.or {
  background: rgba(0,0,0,.28) !important;
  border-color: rgba(255,215,0,.4) !important;
}

/* Sweep reflet — toujours visible */
.tcard::after {
  background: linear-gradient(105deg,
    transparent 28%,
    rgba(255,255,255,.06) 42%,
    rgba(255,255,255,.16) 50%,
    rgba(255,255,255,.06) 58%,
    transparent 72%) !important;
}

/* Overlay visuel card plus subtil sur métal */
.tcard-visual-overlay {
  background: linear-gradient(180deg, rgba(0,0,0,.05) 0%, rgba(0,0,0,.55) 100%) !important;
}

</style>
</head>
<body>

<div class="cur" id="cur"></div>
<div class="cur-r" id="cur-r"></div>
<div class="grain"></div>
<canvas id="hero-canvas"></canvas>

<!-- MODE SWITCHER -->
<div class="msw-wrap">
  <button class="msw on" data-m="night" onclick="setMode('night')">🌙 Nuit</button>
  <button class="msw"    data-m="dawn"  onclick="setMode('dawn')">🌅 Aube</button>
  <button class="msw"    data-m="noon"  onclick="setMode('noon')">🌞 Midi</button>
</div>

<!-- NAV -->
<nav id="nav">
  <a href="#" class="nav-brand">Renait-Sens</a>
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
  <a href="#" onclick="closeMenu()">L'Odyssée</a>
  <a href="#traversees" onclick="closeMenu()">Les Traversées</a>
  <a href="#" onclick="closeMenu()">La Bulle</a>
</div>

<!-- ══ HERO ══ -->
{{-- <section id="hero">
  <div class="hero-sky"></div>
  <div class="hero-overlay"></div>
  <canvas id="hero-canvas-el"></canvas>
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
</section> --}}

<!-- ══ TRAVERSÉES ══ -->
<section class="section" id="traversees">
  <div class="si">
    <span class="s-label reveal">· Les Traversées ·</span>
    <h2 class="s-title reveal rd1">Trois Chemins, Une Transformation</h2>
    <p class="s-intro reveal rd2">L'Offrande n'est pas un prix. C'est un investissement dans ta renaissance.</p>

    <div class="traversees-wrap">

      <!-- ═ BRONZE — REGARD ═ -->
      <div class="tcard reveal">
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
          <a href="{{ route('inscription.form', ['choix' => 'regard']) }}"" class="tcard-cta outline">Commencer ce chemin →</a>
        </div>
      </div>

      <!-- ═ ARGENT — PRÉSENCE ═ -->
      <div class="tcard reveal rd1">
        <div class="badge">Le plus choisi</div>
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
      <div class="tcard reveal rd2">
        <div class="tcard-visual">
          <div class="tcard-bg bg-or"></div>
          <div class="tcard-visual-overlay"></div>
          <span class="tcard-num">Traversée · 03</span>
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
            <div class="sens-txt">Vivre l'expérience du sacré pour revenir transformé à jamais.</div>
          </div>
          <a href="{{ route('inscription.form', ['choix' => 'absolu']) }}" class="tcard-cta outline">Soumettre mon Appel →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="ft-in">
    <span class="ft-brand">Renait-Sens</span>
    <span class="ft-tag">Le sahara ne t'a pas changé — il t'a révélé</span>
  </div>
  <p class="ft-copy">© Renait-Sens · Tassili n'Ajjer · Algérie</p>
</footer>

<script>
const MODES={
  night:{hdu1a:'#1A1208',hdu1b:'#07071A',hdu2a:'#241A0A',hdu2b:'#07071A',crest:'#C9A96E',icon:'🌙',sky:['#03030F','#0A0820','#1A1230']},
  dawn: {hdu1a:'#5C3018',hdu1b:'#160B06',hdu2a:'#7A4520',hdu2b:'#160B06',crest:'#FFB464',icon:'💫',sky:['#1A0E22','#5C2D52','#E8845A']},
  noon: {hdu1a:'#B8942A',hdu1b:'#F5F0E8',hdu2a:'#C8A050',hdu2b:'#EDE8DF',crest:'#FFE066',icon:'🌞',sky:['#4A8FD4','#82BBE8','#C8E0F0']}
};
let currentMode='night';

function setMode(m){
  currentMode=m;
  document.documentElement.setAttribute('data-mode',m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on',b.dataset.m===m));
  const c=MODES[m];
  [['hdu1a',c.hdu1a],['hdu1b',c.hdu1b],['hdu2a',c.hdu2a],['hdu2b',c.hdu2b]].forEach(([id,v])=>{
    const el=document.getElementById(id);if(el)el.setAttribute('stop-color',v);
  });
  const cr=document.getElementById('hcrest');if(cr)cr.setAttribute('stroke',c.crest);
  const hi=document.getElementById('hero-icon');if(hi)hi.textContent=c.icon;
}

// Canvas hero
// (function(){
//   const cv=document.getElementById('hero-canvas-el'),ctx=cv.getContext('2d');
//   let W,H,stars=[];
//   function resize(){
//     W=cv.width=cv.offsetWidth||window.innerWidth;
//     H=cv.height=cv.offsetHeight||window.innerHeight;
//     stars=Array.from({length:220},()=>({
//       x:Math.random()*W,y:Math.random()*H*.68,
//       r:Math.random()*1.1+.15,a:Math.random()*.8+.1,
//       sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
//     }));
//   }
//   function draw(t){
//     ctx.clearRect(0,0,W,H);
//     const m=currentMode;
//     if(m==='night'){
//       stars.forEach(s=>{
//         const a=s.a*(.5+.5*Math.sin(t*s.sp*1000+s.ph));
//         ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
//         ctx.fillStyle=`rgba(245,230,200,${a})`;ctx.fill();
//       });
//       const lx=W*.84,ly=H*.1,p=1+.008*Math.sin(t*.0008);
//       const mg=ctx.createRadialGradient(lx,ly,0,lx,ly,28*p);
//       mg.addColorStop(0,'rgba(245,230,200,.2)');mg.addColorStop(1,'transparent');
//       ctx.beginPath();ctx.arc(lx,ly,28*p,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
//       ctx.beginPath();ctx.arc(lx,ly,11,-Math.PI*.65,Math.PI*.65);
//       ctx.fillStyle='rgba(230,210,165,.12)';ctx.fill();
//     }
//     if(m==='dawn'){
//       stars.slice(0,45).forEach(s=>{
//         ctx.beginPath();ctx.arc(s.x,s.y*.38,s.r*.5,0,Math.PI*2);
//         ctx.fillStyle='rgba(255,240,220,.17)';ctx.fill();
//       });
//       const sx=W*.32,sy=H*.62,p=1+.012*Math.sin(t*.001);
//       [[165,'rgba(255,90,20,.06)'],[98,'rgba(255,160,60,.1)'],[58,'rgba(255,200,100,.16)'],[28,'rgba(255,240,190,.68)']].forEach(([r,col])=>{
//         const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
//         rg.addColorStop(0,col);rg.addColorStop(1,'transparent');
//         ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
//       });
//     }
//     if(m==='noon'){
//       const sx=W*.5,sy=H*.07,p=1+.007*Math.sin(t*.0015);
//       [{r:150,c:'rgba(255,248,180,.055)'},{r:92,c:'rgba(255,230,120,.1)'},{r:54,c:'rgba(255,220,100,.18)'},{r:28,c:'rgba(255,252,220,.55)'}].forEach((({r,c})=>{
//         const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
//         rg.addColorStop(0,c);rg.addColorStop(1,'transparent');
//         ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
//       }));
//     }
//     requestAnimationFrame(draw);
//   }
//   new ResizeObserver(resize).observe(document.getElementById('hero'));
//   resize();requestAnimationFrame(draw);
// })();

// Nav scroll
const navEl=document.getElementById('nav');
window.addEventListener('scroll',()=>navEl.classList.toggle('scrolled',window.scrollY>60));

// Burger
let mOpen=false;
function toggleMenu(){mOpen=!mOpen;document.getElementById('burger').classList.toggle('open',mOpen);document.getElementById('mob-nav').classList.toggle('open',mOpen);document.body.style.overflow=mOpen?'hidden':''}
function closeMenu(){mOpen=false;document.getElementById('burger').classList.remove('open');document.getElementById('mob-nav').classList.remove('open');document.body.style.overflow=''}

// Curseur
const cur=document.getElementById('cur'),curR=document.getElementById('cur-r');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// Reveal
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('v');io.unobserve(e.target)}}),{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// Auto mode
(function(){const h=new Date().getHours();if(h>=5&&h<9)setMode('dawn');else if(h>=9&&h<19)setMode('noon');else setMode('night')})();
</script>
</body>
</html>