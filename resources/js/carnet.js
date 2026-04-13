window.setMode=setMode;
// ══ CURSEUR ══
const cur=document.getElementById('cursor'),curR=document.getElementById('cursor-ring');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// ══ MODE SWITCHER ══
function setMode(m){
  document.documentElement.setAttribute('data-mode',m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on',b.dataset.m===m));
}

// Auto-mode selon heure
(function(){
  const h=new Date().getHours();
  if(h>=5&&h<9) setMode('dawn');
  else if(h>=9&&h<19) setMode('noon');
  else setMode('night');
})();

// ══ NAV SCROLL ══
const nav=document.getElementById('nav');
window.addEventListener('scroll',()=>nav.classList.toggle('scrolled',window.scrollY>60));

// ══ REVEAL ══
const io=new IntersectionObserver(entries=>entries.forEach(e=>{
  if(e.isIntersecting){e.target.classList.add('visible');io.unobserve(e.target)}
}),{threshold:.12});
document.querySelectorAll('.reveal,.tl-step').forEach(el=>io.observe(el));