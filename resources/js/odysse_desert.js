window.setMode_odysse=setMode_odysse

const MODES = {
  night:{
    du1a:'#1A1208', du1b:'#0A0A12', du2a:'#241A0A', du2b:'#0D0D1A',
    crest:'#C9A96E', icon:'🌙', emoji:'⏳',
    hero_sub:'Là où le silence parle',
    appel_sky:['#1A0E08','#3A1808','#C8722A'],
  },
  dawn:{
    du1a:'#5C3018', du1b:'#2A1208', du2a:'#7A4520', du2b:'#3D2010',
    crest:'#FFB464', icon:'💫', emoji:'🌅',
    hero_sub:'L\u2019heure où la lumière naît',
    appel_sky:['#1A0E22','#5C2D52','#E8845A'],
  },
  noon:{
    du1a:'#B8942A', du1b:'#7A5F18', du2a:'#C8A050', du2b:'#8A6820',
    crest:'#FFE066', icon:'🌞', emoji:'🐪',
    hero_sub:'Aucune ombre ne ment sous le zénith',
    appel_sky:['#4A8FD4','#82BBE8','#C8E0F0'],
  }
};

let currentMode = 'night';

function setMode_odysse(m){
  currentMode = m;
  document.documentElement.setAttribute('data-mode', m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on', b.dataset.m===m));
  const c = MODES[m];
  // Dunes
  document.getElementById('du1a').setAttribute('stop-color', c.du1a);
  document.getElementById('du1b').setAttribute('stop-color', c.du1b);
  document.getElementById('du2a').setAttribute('stop-color', c.du2a);
  document.getElementById('du2b').setAttribute('stop-color', c.du2b);
  document.getElementById('dcrest').setAttribute('stroke', c.crest);
  // Icons
  document.getElementById('hero-icon').textContent = c.icon;
  document.getElementById('pacte-em').textContent  = c.emoji;
  document.getElementById('hero-sub').textContent  = c.hero_sub;
}

// ══ BURGER MENU ══
let menuOpen = false;
function toggleMenu(){
  menuOpen = !menuOpen;
  document.getElementById('burger').classList.toggle('open', menuOpen);
  document.getElementById('mobile-nav').classList.toggle('open', menuOpen);
  document.body.style.overflow = menuOpen ? 'hidden' : '';
}
function closeMenu(){
  menuOpen = false;
  document.getElementById('burger').classList.remove('open');
  document.getElementById('mobile-nav').classList.remove('open');
  document.body.style.overflow = '';
}

// ══ CURSEUR ══
const cur=document.getElementById('cursor'), curR=document.getElementById('cursor-ring');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// ══ NAVBAR SCROLL ══
const nav=document.getElementById('nav');
window.addEventListener('scroll',()=>nav.classList.toggle('scrolled',window.scrollY>60));

// ══ HERO PARALLAX ══
const parallaxEl=document.getElementById('hero-parallax');
window.addEventListener('scroll',()=>parallaxEl.style.transform=`translateY(${window.scrollY*.35}px)`);

// ══ HERO CANVAS (étoiles/soleil/aube selon mode) ══
(function(){
  const c=document.getElementById('hero-canvas');
  const ctx=c.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=c.width=window.innerWidth; H=c.height=window.innerHeight;
    stars=Array.from({length:220},()=>({
      x:Math.random()*W, y:Math.random()*H*.68,
      r:Math.random()*1.1+.18, a:Math.random()*.75+.1,
      sp:Math.random()*.003+.001, ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode;
    if(m==='night'){
      stars.forEach(s=>{
        const a=s.a*(.55+.45*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
        ctx.fillStyle=`rgba(245,230,200,${a})`;ctx.fill();
      });
      // Lune
      const lx=W*.82,ly=H*.12,p=1+.008*Math.sin(t*.0008);
      const mg=ctx.createRadialGradient(lx,ly,0,lx,ly,32*p);
      mg.addColorStop(0,'rgba(245,230,200,.2)');mg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(lx,ly,32*p,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
      ctx.beginPath();ctx.arc(lx,ly,13,-Math.PI*.65,Math.PI*.65);
      ctx.fillStyle='rgba(230,210,165,.14)';ctx.fill();
    }
    if(m==='dawn'){
      stars.slice(0,50).forEach(s=>{
        ctx.beginPath();ctx.arc(s.x,s.y*.45,s.r*.65,0,Math.PI*2);
        ctx.fillStyle='rgba(255,240,220,.2)';ctx.fill();
      });
      const sx=W*.36,sy=H*.64,p=1+.012*Math.sin(t*.001);
      [[180,'rgba(255,90,20,.07)'],[110,'rgba(255,160,60,.11)'],[65,'rgba(255,210,110,.17)']].forEach(([r,col])=>{
        const g=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        g.addColorStop(0,col);g.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
      });
      const sg=ctx.createRadialGradient(sx,sy,0,sx,sy,26*p);
      sg.addColorStop(0,'rgba(255,240,200,.9)');sg.addColorStop(.5,'rgba(255,150,50,.65)');sg.addColorStop(1,'rgba(255,80,20,0)');
      ctx.beginPath();ctx.arc(sx,sy,26*p,0,Math.PI*2);ctx.fillStyle=sg;ctx.fill();
      for(let i=0;i<8;i++){
        const a=(i/8)*Math.PI*2+t*.0002,len=52+16*Math.sin(t*.0008+i);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*28,sy+Math.sin(a)*28);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,185,85,${.07+.04*Math.sin(t*.001+i)})`;ctx.lineWidth=1.4;ctx.stroke();
      }
      // Oiseaux
      stars.slice(0,10).forEach((s,i)=>{
        const bx=(s.x+t*(.8+i*.06)*0.05)%W;
        const flap=Math.sin(t*.005+i*1.2)*s.r*1.2;
        ctx.beginPath();
        ctx.moveTo(bx-8,H*.22+s.y*.08+flap);
        ctx.quadraticCurveTo(bx,H*.22+s.y*.08-flap*1.3,bx+8,H*.22+s.y*.08+flap);
        ctx.strokeStyle='rgba(20,6,2,.55)';ctx.lineWidth=1.8;ctx.stroke();
      });
    }
    if(m==='noon'){
      const sx=W*.5,sy=H*.07,p=1+.007*Math.sin(t*.0015);
      [{r:170,c:'rgba(255,248,180,.05)'},{r:105,c:'rgba(255,230,120,.1)'},{r:62,c:'rgba(255,220,100,.18)'},{r:33,c:'rgba(255,252,220,.52)'}].forEach(({r,c})=>{
        const g=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        g.addColorStop(0,c);g.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
      });
      for(let i=0;i<12;i++){
        const a=(i/12)*Math.PI*2+t*.00005,len=118+26*Math.sin(t*.0006+i*.8);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*35,sy+Math.sin(a)*35);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,228,140,${.07+.04*Math.sin(t*.0012+i)})`;ctx.lineWidth=1.9;ctx.stroke();
      }
      // Shimmer
      const sh=ctx.createLinearGradient(0,H*.55,0,H*.65);
      sh.addColorStop(0,'transparent');sh.addColorStop(.5,`rgba(200,160,80,${.06+.04*Math.sin(t*.001)})`);sh.addColorStop(1,'transparent');
      ctx.fillStyle=sh;ctx.fillRect(0,H*.55,W,H*.1);
    }
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',resize);resize();
  requestAnimationFrame(draw);
})();

// ══ CANVAS APPEL ══
(function(){
  const c=document.getElementById('appel-canvas');
  const ctx=c.getContext('2d');
  function resize(){c.width=c.offsetWidth;c.height=c.offsetHeight}
  function draw(t){
    const W=c.width,H=c.height,m=currentMode;
    ctx.clearRect(0,0,W,H);
    const cfg=MODES[m].appel_sky;
    const sky=ctx.createLinearGradient(0,0,0,H*.65);
    sky.addColorStop(0,cfg[0]);sky.addColorStop(.4,cfg[1]);sky.addColorStop(1,cfg[2]);
    ctx.fillStyle=sky;ctx.fillRect(0,0,W,H*.65);
    // Sol
    const gnd=ctx.createLinearGradient(0,H*.6,0,H);
    if(m==='noon'){gnd.addColorStop(0,'#C8A050');gnd.addColorStop(1,'#7A5F18')}
    else if(m==='dawn'){gnd.addColorStop(0,'#7A4522');gnd.addColorStop(1,'#3A1808')}
    else{gnd.addColorStop(0,'#C8722A');gnd.addColorStop(1,'#4A2810')}
    ctx.fillStyle=gnd;ctx.fillRect(0,H*.6,W,H*.4);
    // Dunes
    [[H*.52,H*.15,'#7A3A18',.9],[H*.58,H*.12,'#5A2A10',.95],[H*.65,H*.1,'#3A1808',1]].forEach(([y0,h,col,op])=>{
      ctx.beginPath();ctx.moveTo(0,H);
      ctx.bezierCurveTo(W*.15,y0+h*.3,W*.35,y0-h*.5,W*.5,y0);
      ctx.bezierCurveTo(W*.65,y0+h*.5,W*.82,y0+h*.2,W,y0+h*.4);
      ctx.lineTo(W,H);ctx.closePath();ctx.fillStyle=col;ctx.globalAlpha=op;ctx.fill();ctx.globalAlpha=1;
    });
    // Astre
    const sx=W*.68,sy=m==='noon'?H*.1:H*.22,p=1+.015*Math.sin(t*.0008);
    const colors=m==='noon'
      ?[{r:80,c:'rgba(255,245,180,.08)'},{r:55,c:'rgba(255,220,100,.13)'},{r:35,c:'rgba(255,230,130,.22)'},{r:18,c:'rgba(255,252,220,.65)'}]
      :[{r:80,c:'rgba(255,140,40,.06)'},{r:55,c:'rgba(255,180,60,.1)'},{r:35,c:'rgba(255,210,100,.18)'},{r:20,c:'rgba(255,240,180,.6)'}];
    colors.forEach(({r,c})=>{
      const g=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
      g.addColorStop(0,c);g.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
    });
    requestAnimationFrame(draw);
  }
  new ResizeObserver(()=>resize()).observe(c.parentElement);
  resize();requestAnimationFrame(draw);
})();

// ══ CANVAS DJANET ══
(function(){
  const c=document.getElementById('djanet-canvas');
  const ctx=c.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=c.width=c.offsetWidth||window.innerWidth;
    H=c.height=c.offsetHeight||600;
    stars=Array.from({length:300},()=>({
      x:Math.random()*W,y:Math.random()*H*.7,
      r:Math.random()*1.4+.2,a:Math.random()*.8+.15,
      sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode;
    // Sky
    const sk=ctx.createLinearGradient(0,0,0,H*.75);
    if(m==='night'){sk.addColorStop(0,'#03030F');sk.addColorStop(.5,'#0A0820');sk.addColorStop(1,'#1A1230')}
    else if(m==='dawn'){sk.addColorStop(0,'#1A0E22');sk.addColorStop(.5,'#5C2D52');sk.addColorStop(1,'#E8845A')}
    else{sk.addColorStop(0,'#2A5A8A');sk.addColorStop(.5,'#4A8FD4');sk.addColorStop(1,'#82BBE8')}
    ctx.fillStyle=sk;ctx.fillRect(0,0,W,H*.75);
    // Astre
    if(m==='night'||m==='dawn'){
      stars.forEach(s=>{
        const alp=m==='dawn'?.2:s.a*(.6+.4*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y,s.r*(m==='dawn'?.5:1),0,Math.PI*2);
        ctx.fillStyle=`rgba(245,225,180,${alp})`;ctx.fill();
      });
      const lx=W*.78,ly=H*.14,mg=ctx.createRadialGradient(lx,ly,0,lx,ly,38);
      mg.addColorStop(0,'rgba(245,225,180,.16)');mg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(lx,ly,38,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
      if(m==='night'){ctx.beginPath();ctx.arc(lx,ly,12,-Math.PI*.65,Math.PI*.65);ctx.fillStyle='rgba(245,225,180,.12)';ctx.fill();}
      if(m==='dawn'){// soleil levant
        const sp=1+.01*Math.sin(t*.001);
        const sg=ctx.createRadialGradient(W*.3,H*.55,0,W*.3,H*.55,50*sp);
        sg.addColorStop(0,'rgba(255,230,180,.8)');sg.addColorStop(.6,'rgba(255,140,50,.4)');sg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(W*.3,H*.55,50*sp,0,Math.PI*2);ctx.fillStyle=sg;ctx.fill();
      }
    } else {// noon
      const sp=W*.5,sy2=H*.08,pp=1+.007*Math.sin(t*.0015);
      [{r:120,c:'rgba(255,248,180,.06)'},{r:75,c:'rgba(255,230,120,.12)'},{r:42,c:'rgba(255,252,220,.55)'}].forEach(({r,c})=>{
        const g=ctx.createRadialGradient(sp,sy2,0,sp,sy2,r*pp);
        g.addColorStop(0,c);g.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sp,sy2,r*pp,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
      });
    }
    // Dunes
    const dg=ctx.createLinearGradient(0,H*.62,0,H);
    if(m==='noon'){dg.addColorStop(0,'rgba(160,120,40,.9)');dg.addColorStop(1,'rgba(80,55,10,1)')}
    else if(m==='dawn'){dg.addColorStop(0,'rgba(80,40,10,.9)');dg.addColorStop(1,'rgba(30,12,4,1)')}
    else{dg.addColorStop(0,'rgba(35,18,6,.85)');dg.addColorStop(1,'rgba(10,8,2,1)')}
    ctx.beginPath();
    ctx.moveTo(0,H*.72);ctx.bezierCurveTo(W*.2,H*.58,W*.45,H*.68,W*.6,H*.62);
    ctx.bezierCurveTo(W*.75,H*.56,W*.9,H*.66,W,H*.62);
    ctx.lineTo(W,H);ctx.lineTo(0,H);ctx.closePath();ctx.fillStyle=dg;ctx.fill();
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',resize);resize();requestAnimationFrame(draw);
})();

// ══ SCROLL REVEAL ══
const io=new IntersectionObserver(entries=>entries.forEach(e=>{
  if(e.isIntersecting){e.target.classList.add('visible');io.unobserve(e.target)}
}),{threshold:.1});
document.querySelectorAll('.reveal,.tl-step').forEach(el=>io.observe(el));


// ══ AUTO MODE ══
(function(){
  const h=new Date().getHours();
  if(h>=5&&h<9) setMode_odysse('dawn');
  else if(h>=9&&h<19) setMode_odysse('noon');
  else setMode_odysse('night');
})();