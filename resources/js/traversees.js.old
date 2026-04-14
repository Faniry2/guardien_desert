window.setMode = setMode;
window.toggleMenu = toggleMenu;
// ══ MODES ══
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

// ══ CANVAS HERO ══
(function(){
  const cv=document.getElementById('hero-canvas'),ctx=cv.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=cv.width=cv.offsetWidth||window.innerWidth;
    H=cv.height=cv.offsetHeight||window.innerHeight;
    stars=Array.from({length:220},()=>({
      x:Math.random()*W,y:Math.random()*H*.68,
      r:Math.random()*1.1+.15,a:Math.random()*.8+.1,
      sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode;
    if(m==='night'){
      stars.forEach(s=>{
        const a=s.a*(.5+.5*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
        ctx.fillStyle=`rgba(245,230,200,${a})`;ctx.fill();
      });
      const lx=W*.84,ly=H*.1,p=1+.008*Math.sin(t*.0008);
      const mg=ctx.createRadialGradient(lx,ly,0,lx,ly,28*p);
      mg.addColorStop(0,'rgba(245,230,200,.2)');mg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(lx,ly,28*p,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
      ctx.beginPath();ctx.arc(lx,ly,11,-Math.PI*.65,Math.PI*.65);
      ctx.fillStyle='rgba(230,210,165,.12)';ctx.fill();
    }
    if(m==='dawn'){
      stars.slice(0,45).forEach(s=>{
        ctx.beginPath();ctx.arc(s.x,s.y*.38,s.r*.5,0,Math.PI*2);
        ctx.fillStyle='rgba(255,240,220,.17)';ctx.fill();
      });
      const sx=W*.32,sy=H*.62,p=1+.012*Math.sin(t*.001);
      [[165,'rgba(255,90,20,.06)'],[98,'rgba(255,160,60,.1)'],[58,'rgba(255,200,100,.16)'],[28,'rgba(255,240,190,.68)']].forEach(([r,col])=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        rg.addColorStop(0,col);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      for(let i=0;i<8;i++){
        const a=(i/8)*Math.PI+t*.0001,len=75+18*Math.sin(t*.0008+i);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*30,sy+Math.sin(a)*30);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,180,80,${.06+.03*Math.sin(t*.001+i)})`;ctx.lineWidth=1.3;ctx.stroke();
      }
      stars.slice(0,9).forEach((s,i)=>{
        const bx=(s.x+t*(.7+i*.05)*.05)%W;
        const fl=Math.sin(t*.005+i*1.2)*s.r;
        ctx.beginPath();ctx.moveTo(bx-7,H*.22+s.y*.06+fl);
        ctx.quadraticCurveTo(bx,H*.22+s.y*.06-fl*1.2,bx+7,H*.22+s.y*.06+fl);
        ctx.strokeStyle='rgba(20,6,2,.5)';ctx.lineWidth=1.7;ctx.stroke();
      });
    }
    if(m==='noon'){
      const sx=W*.5,sy=H*.07,p=1+.007*Math.sin(t*.0015);
      [{r:150,c:'rgba(255,248,180,.055)'},{r:92,c:'rgba(255,230,120,.1)'},{r:54,c:'rgba(255,220,100,.18)'},{r:28,c:'rgba(255,252,220,.55)'}].forEach(({r,c})=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        rg.addColorStop(0,c);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      for(let i=0;i<12;i++){
        const a=(i/12)*Math.PI*2+t*.00005,len=108+22*Math.sin(t*.0006+i*.8);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*30,sy+Math.sin(a)*30);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,228,140,${.06+.04*Math.sin(t*.0012+i)})`;ctx.lineWidth=1.8;ctx.stroke();
      }
    }
    requestAnimationFrame(draw);
  }
  new ResizeObserver(resize).observe(document.getElementById('hero'));
  resize();requestAnimationFrame(draw);
})();

// ══ CANVAS CTA ══
(function(){
  const cv=document.getElementById('cta-canvas'),ctx=cv.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=cv.width=cv.offsetWidth||window.innerWidth;
    H=cv.height=cv.offsetHeight||500;
    stars=Array.from({length:280},()=>({
      x:Math.random()*W,y:Math.random()*H,
      r:Math.random()*1.3+.2,a:Math.random()*.7+.1,
      sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode,sk=MODES[m].sky;
    const g=ctx.createLinearGradient(0,0,0,H);
    g.addColorStop(0,sk[0]);g.addColorStop(.5,sk[1]);g.addColorStop(1,sk[2]);
    ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
    if(m==='night'||m==='dawn'){
      stars.forEach(s=>{
        const al=s.a*(m==='dawn'?.25:1)*(.55+.45*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y*(m==='dawn'?.5:1),s.r*(m==='dawn'?.6:1),0,Math.PI*2);
        ctx.fillStyle=`rgba(245,225,180,${al})`;ctx.fill();
      });
    }
    if(m==='noon'){
      const sx=W*.5,sy=H*.05,p=1+.007*Math.sin(t*.0015);
      [{r:110,c:'rgba(255,248,180,.07)'},{r:65,c:'rgba(255,230,120,.13)'},{r:35,c:'rgba(255,252,220,.5)'}].forEach(({r,c})=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        rg.addColorStop(0,c);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
    }
    // Dunes basses
    const dg=ctx.createLinearGradient(0,H*.6,0,H);
    if(m==='noon'){dg.addColorStop(0,'rgba(160,120,40,.85)');dg.addColorStop(1,'rgba(80,55,10,1)')}
    else if(m==='dawn'){dg.addColorStop(0,'rgba(80,40,10,.88)');dg.addColorStop(1,'rgba(22,11,6,1)')}
    else{dg.addColorStop(0,'rgba(30,16,6,.85)');dg.addColorStop(1,'rgba(7,7,26,1)')}
    ctx.beginPath();
    ctx.moveTo(0,H*.72);ctx.bezierCurveTo(W*.2,H*.58,W*.45,H*.68,W*.6,H*.62);
    ctx.bezierCurveTo(W*.75,H*.56,W*.9,H*.66,W,H*.62);
    ctx.lineTo(W,H);ctx.lineTo(0,H);ctx.closePath();
    ctx.fillStyle=dg;ctx.fill();
    requestAnimationFrame(draw);
  }
  new ResizeObserver(resize).observe(document.getElementById('cta-final'));
  resize();requestAnimationFrame(draw);
})();

// ══ BG CANVAS (fixe) ══
(function(){
  const cv=document.getElementById('bg-canvas'),ctx=cv.getContext('2d');
  let W,H;
  function resize(){W=cv.width=window.innerWidth;H=cv.height=window.innerHeight}
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode;
    if(m==='night'||m==='dawn'){
      // Légère lueur acc en bas à droite
      const rg=ctx.createRadialGradient(W*.85,H*.85,0,W*.85,H*.85,W*.4);
      rg.addColorStop(0,m==='night'?'rgba(212,98,42,.04)'  :'rgba(224,90,32,.04)');
      rg.addColorStop(1,'transparent');
      ctx.fillStyle=rg;ctx.fillRect(0,0,W,H);
    }
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',resize);resize();requestAnimationFrame(draw);
})();

// ══ NAVBAR ══
const navEl=document.getElementById('nav');
window.addEventListener('scroll',()=>navEl.classList.toggle('scrolled',window.scrollY>60));

// ══ BURGER ══
let mOpen=false;
function toggleMenu(){mOpen=!mOpen;document.getElementById('burger').classList.toggle('open',mOpen);document.getElementById('mob-nav').classList.toggle('open',mOpen);document.body.style.overflow=mOpen?'hidden':''}
function closeMenu(){mOpen=false;document.getElementById('burger').classList.remove('open');document.getElementById('mob-nav').classList.remove('open');document.body.style.overflow=''}

// ══ CURSEUR ══
const cur=document.getElementById('cur'),curR=document.getElementById('cur-r');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// ══ REVEAL ══
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('v');io.unobserve(e.target)}}),{threshold:.1});
document.querySelectorAll('.reveal,.reveal-left,.reveal-right').forEach(el=>io.observe(el));

// ══ AUTO MODE ══
(function(){const h=new Date().getHours();if(h>=5&&h<9)setMode('dawn');else if(h>=9&&h<19)setMode('noon');else setMode('night')})();
