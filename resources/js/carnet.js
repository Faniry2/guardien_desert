window.setMode = setMode;

// ══ CURSEUR ══
const cur  = document.getElementById('cursor');
const curR = document.getElementById('cursor-ring');
let mx=0, my=0, rx=0, ry=0;
document.addEventListener('mousemove', e => {
  mx=e.clientX; my=e.clientY;
  cur.style.left=mx+'px'; cur.style.top=my+'px';
});
(function ac(){
  rx+=(mx-rx)*.12; ry+=(my-ry)*.12;
  curR.style.left=rx+'px'; curR.style.top=ry+'px';
  requestAnimationFrame(ac);
})();

// ══ MODE SWITCHER ══
function setMode(m){
  document.documentElement.setAttribute('data-mode', m);
  document.querySelectorAll('.msw').forEach(b => b.classList.toggle('on', b.dataset.m===m));

  const canvasNight = document.getElementById('hero-star-canvas');
  const field       = document.getElementById('hero-star-field');
  const canvasNoon  = document.getElementById('hero-noon-canvas');
  const canvasDawn  = document.getElementById('hero-dawn-canvas');

  if(canvasNight){
    canvasNight.style.opacity       = (m==='night') ? '1' : '0';
    canvasNight.style.pointerEvents = 'none';
  }
  if(field)      field.style.opacity      = (m==='night') ? '1' : '0';
  if(canvasNoon) canvasNoon.style.opacity = (m==='noon')  ? '1' : '0';
  if(canvasDawn) canvasDawn.style.opacity = (m==='dawn')  ? '1' : '0';
}

(function(){
  const h = new Date().getHours();
  if(h>=5&&h<9)       setMode('dawn');
  else if(h>=9&&h<19) setMode('noon');
  else                 setMode('night');
})();

// ══ NAV SCROLL ══
const nav = document.getElementById('nav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY>60));

// ══ REVEAL ══
const io = new IntersectionObserver(entries => entries.forEach(e => {
  if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
}), {threshold:.12});
document.querySelectorAll('.reveal,.tl-step').forEach(el => io.observe(el));

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
window.toggleMenu = toggleMenu;
window.closeMenu  = closeMenu;

// ════════════════════════════════════════════
// ══ ÉTOILES INTERACTIVES — HERO NUIT ══
// ════════════════════════════════════════════
(function(){
  const canvas = document.getElementById('hero-star-canvas');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H;
  const mouse = { x:-9999, y:-9999, active:false };

  window.addEventListener('mousemove', e => {
    const rect = canvas.getBoundingClientRect();
    if(e.clientY < rect.bottom && e.clientY > rect.top){
      mouse.x = e.clientX - rect.left;
      mouse.y = e.clientY - rect.top;
      mouse.active = true;
    } else { mouse.active = false; }
  });
  window.addEventListener('touchmove', e => {
    const rect=canvas.getBoundingClientRect(), touch=e.touches[0];
    if(touch.clientY < rect.bottom){ mouse.x=touch.clientX-rect.left; mouse.y=touch.clientY-rect.top; mouse.active=true; }
  }, {passive:true});
  window.addEventListener('touchend', ()=>{ setTimeout(()=>{mouse.active=false;},1500); });
  window.addEventListener('click', e => {
    const rect=canvas.getBoundingClientRect();
    if(e.clientY<rect.bottom&&e.clientY>rect.top) addBurst(e.clientX-rect.left,e.clientY-rect.top);
  });

  function resize(){ W=canvas.width=canvas.offsetWidth||window.innerWidth; H=canvas.height=canvas.offsetHeight||window.innerHeight; }

  const starField=document.getElementById('hero-star-field');
  function buildStarField(){
    if(!starField) return; starField.innerHTML='';
    const SKY=0.68,types=['gold','blue','white','amber'],weights=[0.28,0.22,0.38,0.12];
    const hotspots=[{cx:.55,cy:.18,rx:.38,ry:.28},{cx:.72,cy:.12,rx:.22,ry:.18},{cx:.25,cy:.22,rx:.20,ry:.16}];
    function pickType(){let r=Math.random(),cum=0;for(let i=0;i<types.length;i++){cum+=weights[i];if(r<cum)return types[i];}return 'white';}
    for(let i=0;i<320;i++){
      let xr=Math.random(),yr=Math.random()*SKY;
      for(const hs of hotspots){const dx=(xr-hs.cx)/hs.rx,dy=(yr-hs.cy)/hs.ry;if(dx*dx+dy*dy<1&&Math.random()>.45){xr=hs.cx+(Math.random()-.5)*hs.rx*2;yr=Math.max(0,Math.min(SKY,hs.cy+(Math.random()-.5)*hs.ry*2));break;}}
      const type=pickType(),size=(type==='gold'||type==='amber')?(1.1+Math.random()*3):(0.7+Math.random()*2.2);
      const el=document.createElement('div'); el.className='hs-star hs-'+type;
      el.style.cssText=['left:'+(xr*100).toFixed(2)+'%','top:'+(yr*100).toFixed(2)+'%','width:'+size.toFixed(1)+'px','height:'+size.toFixed(1)+'px','--dur:'+(2.8+Math.random()*5.5).toFixed(2)+'s','--delay:-'+(Math.random()*7).toFixed(2)+'s','--glow:'+(size*2.5).toFixed(1)+'px','--glow-half:'+(size*1.2).toFixed(1)+'px'].join(';');
      starField.appendChild(el);
    }
  }

  const NODE_COUNT=55,CONNECT_DIST=160,MOUSE_DIST=200;
  let nodes=[];
  function initNodes(){
    nodes=[];
    for(let i=0;i<NODE_COUNT;i++){
      nodes.push({x:Math.random()*W,y:Math.random()*H*.70,vx:(Math.random()-.5)*.35,vy:(Math.random()-.5)*.25,r:1.5+Math.random()*2.8,baseR:0,pulse:Math.random()*Math.PI*2,pulseSpeed:.012+Math.random()*.018,color:Math.random()>.5?{r:255,g:200,b:120}:{r:200,g:220,b:255}});
      nodes[i].baseR=nodes[i].r;
    }
  }
  let parts=[];
  function initParts(){
    parts=[];
    const cols=[[255,220,100],[255,255,255],[150,200,255],[255,160,50]];
    for(let i=0;i<100;i++){const c=cols[Math.floor(Math.random()*cols.length)];parts.push({x:Math.random()*W,y:Math.random()*H*.65,r:.3+Math.random()*1.1,op:.08+Math.random()*.42,dop:(.008+Math.random()*.004)*(Math.random()>.5?1:-1),vx:(Math.random()-.5)*.07,vy:-(Math.random()*.055+.01),col:c});}
  }
  let bursts=[];
  function addBurst(x,y){
    const count=18+Math.floor(Math.random()*12);
    for(let i=0;i<count;i++){const angle=(Math.PI*2/count)*i+Math.random()*.4,speed=1.5+Math.random()*3.5,cols=[[255,220,100],[255,255,255],[200,180,255],[255,160,50]];bursts.push({x,y,vx:Math.cos(angle)*speed,vy:Math.sin(angle)*speed,r:.8+Math.random()*1.8,op:.9,col:cols[Math.floor(Math.random()*cols.length)],life:1,decay:.018+Math.random()*.025});}
  }
  let t=0;
  function draw(){
    t++;ctx.clearRect(0,0,W,H);
    const gx=W*.55,gy=H*.22,g=ctx.createRadialGradient(gx,gy,0,gx,gy,W*.3);
    g.addColorStop(0,`rgba(255,180,60,${.04+.02*Math.sin(t*.0006)})`);g.addColorStop(.4,`rgba(255,140,30,${.02+.01*Math.sin(t*.0006)})`);g.addColorStop(1,'rgba(255,140,30,0)');
    ctx.save();ctx.translate(gx,gy);ctx.rotate(-0.26);ctx.scale(1.6,.7);ctx.translate(-gx,-gy);ctx.beginPath();ctx.arc(gx,gy,W*.3,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();ctx.restore();
    parts.forEach(p=>{p.op+=p.dop;if(p.op>.55||p.op<.04)p.dop*=-1;p.x+=p.vx;p.y+=p.vy;if(p.y<-5)p.y=H*.65;if(p.x<0)p.x=W;if(p.x>W)p.x=0;const g2=ctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r*3);g2.addColorStop(0,`rgba(${p.col},${p.op})`);g2.addColorStop(.5,`rgba(${p.col},${p.op*.3})`);g2.addColorStop(1,`rgba(${p.col},0)`);ctx.beginPath();ctx.arc(p.x,p.y,p.r*3,0,Math.PI*2);ctx.fillStyle=g2;ctx.fill();ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle=`rgba(${p.col},${Math.min(p.op+.15,.75)})`;ctx.fill();});
    nodes.forEach(n=>{n.x+=n.vx;n.y+=n.vy;if(n.x<0||n.x>W)n.vx*=-1;if(n.y<0||n.y>H*.72)n.vy*=-1;if(mouse.active){const dx=mouse.x-n.x,dy=mouse.y-n.y,dist=Math.sqrt(dx*dx+dy*dy);if(dist<MOUSE_DIST&&dist>0){const f=(1-dist/MOUSE_DIST)*.025;n.vx+=dx*f;n.vy+=dy*f;}}n.vx*=.988;n.vy*=.988;const speed=Math.sqrt(n.vx*n.vx+n.vy*n.vy);if(speed>3){n.vx=(n.vx/speed)*3;n.vy=(n.vy/speed)*3;}n.pulse+=n.pulseSpeed;const pr=n.baseR+Math.sin(n.pulse)*n.baseR*.55;const gn=ctx.createRadialGradient(n.x,n.y,0,n.x,n.y,pr*5);gn.addColorStop(0,`rgba(${n.color.r},${n.color.g},${n.color.b},.65)`);gn.addColorStop(.4,`rgba(${n.color.r},${n.color.g},${n.color.b},.18)`);gn.addColorStop(1,`rgba(${n.color.r},${n.color.g},${n.color.b},0)`);ctx.beginPath();ctx.arc(n.x,n.y,pr*5,0,Math.PI*2);ctx.fillStyle=gn;ctx.fill();ctx.beginPath();ctx.arc(n.x,n.y,pr,0,Math.PI*2);ctx.fillStyle=`rgba(${n.color.r},${n.color.g},${n.color.b},.85)`;ctx.fill();});
    for(let i=0;i<nodes.length;i++){for(let j=i+1;j<nodes.length;j++){const a=nodes[i],b=nodes[j],dx=a.x-b.x,dy=a.y-b.y,dist=Math.sqrt(dx*dx+dy*dy);if(dist<CONNECT_DIST){ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);ctx.strokeStyle=`rgba(200,210,255,${(1-dist/CONNECT_DIST)*.28})`;ctx.lineWidth=.6;ctx.stroke();}}}
    if(mouse.active){nodes.forEach(n=>{const dx=mouse.x-n.x,dy=mouse.y-n.y,dist=Math.sqrt(dx*dx+dy*dy);if(dist<MOUSE_DIST*1.3){const alpha=(1-dist/(MOUSE_DIST*1.3))*.6;const gl=ctx.createLinearGradient(mouse.x,mouse.y,n.x,n.y);gl.addColorStop(0,`rgba(255,220,150,${alpha})`);gl.addColorStop(1,`rgba(${n.color.r},${n.color.g},${n.color.b},${alpha*.5})`);ctx.beginPath();ctx.moveTo(mouse.x,mouse.y);ctx.lineTo(n.x,n.y);ctx.strokeStyle=gl;ctx.lineWidth=.9;ctx.stroke();}});const cg=ctx.createRadialGradient(mouse.x,mouse.y,0,mouse.x,mouse.y,28);cg.addColorStop(0,'rgba(255,220,150,.55)');cg.addColorStop(.5,'rgba(255,200,100,.18)');cg.addColorStop(1,'rgba(255,200,100,0)');ctx.beginPath();ctx.arc(mouse.x,mouse.y,28,0,Math.PI*2);ctx.fillStyle=cg;ctx.fill();}
    bursts=bursts.filter(b=>b.life>0);bursts.forEach(b=>{b.x+=b.vx;b.y+=b.vy;b.vx*=.94;b.vy*=.94;b.life-=b.decay;b.op=b.life*.9;const bg=ctx.createRadialGradient(b.x,b.y,0,b.x,b.y,b.r*4);bg.addColorStop(0,`rgba(${b.col},${b.op})`);bg.addColorStop(.5,`rgba(${b.col},${b.op*.4})`);bg.addColorStop(1,`rgba(${b.col},0)`);ctx.beginPath();ctx.arc(b.x,b.y,b.r*4,0,Math.PI*2);ctx.fillStyle=bg;ctx.fill();ctx.beginPath();ctx.arc(b.x,b.y,b.r,0,Math.PI*2);ctx.fillStyle=`rgba(${b.col},${Math.min(b.op+.2,1)})`;ctx.fill();});
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',()=>{resize();initNodes();initParts();});
  resize();initNodes();initParts();buildStarField();draw();
})();

// ════════════════════════════════════════════════════════
// ══ VRAIE FLAMME — HERO MIDI (noon) ══
// ════════════════════════════════════════════════════════
(function(){
  const canvas = document.getElementById('hero-noon-canvas');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H;
  const mouse = { x:-9999, y:-9999, px:-9999, py:-9999, active:false };
  const trail = [];
  const TRAIL_LEN = 28;

  window.addEventListener('mousemove', e => {
    const rect = canvas.getBoundingClientRect();
    mouse.px=mouse.x; mouse.py=mouse.y;
    mouse.x=e.clientX-rect.left; mouse.y=e.clientY-rect.top;
    mouse.active=(e.clientY>rect.top&&e.clientY<rect.bottom);
    if(mouse.active){ trail.unshift({x:mouse.x,y:mouse.y,t:Date.now()}); if(trail.length>TRAIL_LEN)trail.pop(); }
  });
  window.addEventListener('mouseleave',()=>{mouse.active=false;});
  window.addEventListener('touchmove',e=>{
    const rect=canvas.getBoundingClientRect(),touch=e.touches[0];
    mouse.px=mouse.x;mouse.py=mouse.y;mouse.x=touch.clientX-rect.left;mouse.y=touch.clientY-rect.top;mouse.active=true;
    trail.unshift({x:mouse.x,y:mouse.y,t:Date.now()});if(trail.length>TRAIL_LEN)trail.pop();
  },{passive:true});
  window.addEventListener('touchend',()=>{setTimeout(()=>{mouse.active=false;trail.length=0;},400);});

  function resize(){ W=canvas.width=canvas.offsetWidth||window.innerWidth; H=canvas.height=canvas.offsetHeight||window.innerHeight; }

  const MAX_PARTICLES=600;
  let particles=[];
  function createParticle(x,y,trailIdx){
    const progress=trailIdx/TRAIL_LEN;
    const spread=(1-progress)*18+3;
    const angle=-Math.PI/2+(Math.random()-.5)*1.4;
    const speed=(2+Math.random()*4)*(1-progress*.5);
    const size=(6+Math.random()*14)*(1-progress*.65);
    const maxLife=.6+Math.random()*.4;
    return{x:x+(Math.random()-.5)*spread*2,y:y+(Math.random()-.5)*spread*.5,vx:Math.cos(angle)*speed*.4+(Math.random()-.5)*1.5,vy:Math.sin(angle)*speed,turbX:(Math.random()-.5)*.8,turbY:-(Math.random()*.3),r:size,rMin:size*.05,life:maxLife,maxLife,heat:Math.random()*.5+(1-progress)*.5};
  }
  function flameColor(heat,alpha){
    if(heat>.85)return`rgba(255,250,220,${alpha})`;
    if(heat>.7){const t=(heat-.7)/.15;return`rgba(255,${Math.round(200+55*t)},${Math.round(50*t)},${alpha})`;}
    if(heat>.5){const t=(heat-.5)/.2;return`rgba(255,${Math.round(80+120*t)},0,${alpha})`;}
    if(heat>.3){const t=(heat-.3)/.2;return`rgba(${Math.round(180+75*t)},${Math.round(40+40*t)},0,${alpha})`;}
    if(heat>.1){const t=(heat-.1)/.2;return`rgba(${Math.round(80+100*t)},${Math.round(10*t)},0,${alpha})`;}
    return`rgba(30,10,0,${alpha*.5})`;
  }
  let embers=[];
  function spawnEmber(x,y){embers.push({x:x+(Math.random()-.5)*20,y,vx:(Math.random()-.5)*2,vy:-(1+Math.random()*2.5),r:.8+Math.random()*1.8,life:1,decay:.008+Math.random()*.015,heat:.6+Math.random()*.4});}
  let frameCount=0;
  function draw(){
    frameCount++;
    const mode=document.documentElement.getAttribute('data-mode');
    if(mode!=='noon'){requestAnimationFrame(draw);return;}
    ctx.clearRect(0,0,W,H);
    if(mouse.active&&trail.length>1){
      trail.forEach((pt,i)=>{
        const count=i===0?6:Math.max(1,Math.round(3*(1-i/TRAIL_LEN)));
        for(let k=0;k<count;k++){if(particles.length<MAX_PARTICLES)particles.push(createParticle(pt.x,pt.y,i));}
        if(i<5&&Math.random()<.3&&embers.length<80)spawnEmber(pt.x,pt.y);
      });
    }
    ctx.globalCompositeOperation='screen';
    particles=particles.filter(p=>p.life>0);
    particles.forEach(p=>{
      p.x+=p.vx+p.turbX*Math.sin(frameCount*.08+p.x*.03);p.y+=p.vy+p.turbY;
      p.vx*=.97;p.vy-=.05+Math.random()*.04;p.vy*=.97;p.r=Math.max(p.rMin,p.r*.978);p.life-=.022+Math.random()*.01;
      const progress=1-(p.life/p.maxLife),currentHeat=p.heat*(1-progress*.8),alpha=Math.max(0,p.life/p.maxLife)*.9;
      if(alpha<=0||p.r<.5)return;
      const g1=ctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r*2.8);
      g1.addColorStop(0,flameColor(currentHeat,alpha*.6));g1.addColorStop(.3,flameColor(currentHeat,alpha*.25));g1.addColorStop(1,flameColor(currentHeat,0));
      ctx.beginPath();ctx.arc(p.x,p.y,p.r*2.8,0,Math.PI*2);ctx.fillStyle=g1;ctx.fill();
      const g2=ctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r);
      g2.addColorStop(0,flameColor(Math.min(1,currentHeat+.2),alpha));g2.addColorStop(.5,flameColor(currentHeat,alpha*.8));g2.addColorStop(1,flameColor(Math.max(0,currentHeat-.3),alpha*.2));
      ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle=g2;ctx.fill();
    });
    embers=embers.filter(e=>e.life>0);
    embers.forEach(e=>{
      e.x+=e.vx+Math.sin(frameCount*.05+e.x*.02)*.4;e.y+=e.vy;e.vy-=.02;e.vx*=.99;e.life-=e.decay;
      const alpha=e.life*.95,g=ctx.createRadialGradient(e.x,e.y,0,e.x,e.y,e.r*3);
      g.addColorStop(0,flameColor(e.heat,alpha));g.addColorStop(.4,flameColor(e.heat*.7,alpha*.4));g.addColorStop(1,flameColor(e.heat*.4,0));
      ctx.beginPath();ctx.arc(e.x,e.y,e.r*3,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
      ctx.beginPath();ctx.arc(e.x,e.y,e.r,0,Math.PI*2);ctx.fillStyle=`rgba(255,240,180,${alpha})`;ctx.fill();
    });
    ctx.globalCompositeOperation='source-over';
    if(mouse.active&&mouse.x>0){
      const cg=ctx.createRadialGradient(mouse.x,mouse.y,0,mouse.x,mouse.y,55);
      cg.addColorStop(0,'rgba(255,180,30,.22)');cg.addColorStop(.4,'rgba(255,80,0,.08)');cg.addColorStop(1,'rgba(255,30,0,0)');
      ctx.beginPath();ctx.arc(mouse.x,mouse.y,55,0,Math.PI*2);ctx.fillStyle=cg;ctx.fill();
    }
    const now=Date.now();while(trail.length&&now-trail[trail.length-1].t>300)trail.pop();
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',()=>{resize();});
  resize();draw();
})();

// ════════════════════════════════════════════════════════
// ══ DÉCHIRURE — HERO AUBE (dawn) ══
// La souris déchire l'image, révélant le noir derrière
// Les bords sont irréguliers comme du papier arraché
// ════════════════════════════════════════════════════════
(function(){
  const canvas = document.getElementById('hero-dawn-canvas');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H;

  // Historique de la souris pour la traîne de déchirure
  const trail     = [];   // points de déchirure
  const MAX_TRAIL = 60;   // longueur max de la déchirure gardée

  // Chaque point de trail contient un profil de déchirure (bords irréguliers)
  const mouse = { x:-9999, y:-9999, active:false };

  window.addEventListener('mousemove', e => {
    const rect = canvas.getBoundingClientRect();
    if(e.clientY > rect.top && e.clientY < rect.bottom){
      mouse.x = e.clientX - rect.left;
      mouse.y = e.clientY - rect.top;
      mouse.active = true;
      // Ajouter le point avec bords irréguliers pré-calculés
      trail.unshift({
        x: mouse.x,
        y: mouse.y,
        // Largeur de la déchirure à ce point (varie aléatoirement)
        w: 8 + Math.random() * 6,
        // Points du bord gauche et droit (dents de scie)
        age: 0,
      });
      if(trail.length > MAX_TRAIL) trail.pop();
    } else {
      mouse.active = false;
    }
  });
  window.addEventListener('mouseleave', () => { mouse.active = false; });
  window.addEventListener('touchmove', e => {
    const rect=canvas.getBoundingClientRect(), touch=e.touches[0];
    if(touch.clientY<rect.bottom){
      mouse.x=touch.clientX-rect.left; mouse.y=touch.clientY-rect.top; mouse.active=true;
      trail.unshift({x:mouse.x,y:mouse.y,w:28+Math.random()*22,age:0});
      if(trail.length>MAX_TRAIL)trail.pop();
    }
  },{passive:true});
  window.addEventListener('touchend',()=>{ setTimeout(()=>{ mouse.active=false; },800); });

  function resize(){
    W=canvas.width=canvas.offsetWidth||window.innerWidth;
    H=canvas.height=canvas.offsetHeight||window.innerHeight;
  }

  // Génère un profil de bord déchiré (suite de points irréguliers)
  // le long d'un segment entre deux points
  function jaggedEdge(x1,y1,x2,y2, amplitude, steps){
    const pts=[];
    const dx=x2-x1, dy=y2-y1;
    const len=Math.sqrt(dx*dx+dy*dy);
    if(len<1) return pts;
    // Perpendiculaire
    const nx=-dy/len, ny=dx/len;
    for(let i=0;i<=steps;i++){
      const t=i/steps;
      const bx=x1+dx*t, by=y1+dy*t;
      // Bruit pseudo-aléatoire déterministe (pas de Math.random ici)
      const noise=Math.sin(i*2.3+x1*.05)*Math.cos(i*1.7+y1*.04)*amplitude;
      pts.push({x:bx+nx*noise, y:by+ny*noise});
    }
    return pts;
  }

  // Particules de débris (petits fragments qui tombent)
  let debris=[];
  function spawnDebris(x,y){
    for(let i=0;i<3;i++){
      debris.push({
        x,y,
        vx:(Math.random()-.5)*3,
        vy:-(Math.random()*2+.5),
        r:1+Math.random()*3,
        life:1,
        decay:.02+Math.random()*.03,
        dark: Math.random()>.5, // noir ou couleur image
      });
    }
  }

  let frameCount=0;
  function draw(){
    frameCount++;
    const mode=document.documentElement.getAttribute('data-mode');
    if(mode!=='dawn'){ requestAnimationFrame(draw); return; }

    ctx.clearRect(0,0,W,H);

    if(trail.length < 2){ requestAnimationFrame(draw); return; }

    // ── Vieillir les points ──
    trail.forEach(p=>{ p.age++; });
    // Supprimer les points trop vieux (la déchirure se referme)
    while(trail.length && trail[trail.length-1].age > 180) trail.pop();

    if(trail.length < 2){ requestAnimationFrame(draw); return; }

    // ── Dessiner la zone déchirée ──
    // On trace un polygone irrégulier autour de tous les points de trail

    // Bord gauche (en allant du plus récent au plus vieux)
    const leftPts  = [];
    const rightPts = [];

    for(let i=0; i<trail.length-1; i++){
      const p  = trail[i];
      const p2 = trail[i+1];
      const dx = p.x-p2.x, dy=p.y-p2.y;
      const len=Math.sqrt(dx*dx+dy*dy)||1;
      const nx=-dy/len, ny=dx/len; // perpendiculaire

      // Largeur réduite avec l'âge
      const ageFactor = 1 - (p.age/180);
      const halfW = p.w * ageFactor * .5;

      // Bords irréguliers avec bruit
      const noise1 = Math.sin(i*3.1+frameCount*.04)*8 + Math.cos(i*1.8)*5;
      const noise2 = Math.sin(i*2.7+frameCount*.03+1.5)*8 + Math.cos(i*2.2+1)*5;

      leftPts.push({
        x: p.x + nx*(halfW+noise1),
        y: p.y + ny*(halfW+noise1),
      });
      rightPts.push({
        x: p.x - nx*(halfW+noise2),
        y: p.y - ny*(halfW+noise2),
      });
    }

    // Construire le polygone fermé
    const allPts = [...leftPts, ...[...rightPts].reverse()];
    if(allPts.length < 3){ requestAnimationFrame(draw); return; }

    // ── Zone noire intérieure (le trou) ──
    ctx.save();
    ctx.beginPath();
    ctx.moveTo(allPts[0].x, allPts[0].y);
    for(let i=1;i<allPts.length;i++){
      // Courbes douces pour plus de naturel
      const prev=allPts[i-1];
      const curr=allPts[i];
      const cpx=(prev.x+curr.x)/2, cpy=(prev.y+curr.y)/2;
      ctx.quadraticCurveTo(prev.x,prev.y,cpx,cpy);
    }
    ctx.closePath();

    // Fond noir profond (comme le vide derrière)
    ctx.fillStyle='rgba(2,2,8,1)';
    ctx.fill();

    // ── Bord déchiré — côté gauche ──
    ctx.beginPath();
    if(leftPts.length>1){
      ctx.moveTo(leftPts[0].x,leftPts[0].y);
      for(let i=1;i<leftPts.length;i++){
        const prev=leftPts[i-1], curr=leftPts[i];
        // Dents de scie irrégulières
        const midX=(prev.x+curr.x)/2+(Math.sin(i*4.2+frameCount*.06)*4);
        const midY=(prev.y+curr.y)/2+(Math.cos(i*3.8+frameCount*.05)*4);
        ctx.quadraticCurveTo(prev.x,prev.y,midX,midY);
      }
    }
    ctx.strokeStyle='rgba(0,0,0,.95)';
    ctx.lineWidth=1;
    ctx.stroke();

    // Ombre portée bord gauche
    ctx.shadowColor='rgba(0,0,0,.8)';
    ctx.shadowBlur=12;
    ctx.shadowOffsetX=3;
    ctx.stroke();
    ctx.shadowBlur=0; ctx.shadowOffsetX=0;

    // ── Bord déchiré — côté droit ──
    ctx.beginPath();
    if(rightPts.length>1){
      ctx.moveTo(rightPts[0].x,rightPts[0].y);
      for(let i=1;i<rightPts.length;i++){
        const prev=rightPts[i-1], curr=rightPts[i];
        const midX=(prev.x+curr.x)/2+(Math.sin(i*3.5+frameCount*.07+2)*4);
        const midY=(prev.y+curr.y)/2+(Math.cos(i*4.1+frameCount*.06+2)*4);
        ctx.quadraticCurveTo(prev.x,prev.y,midX,midY);
      }
    }
    ctx.strokeStyle='rgba(0,0,0,.95)';
    ctx.lineWidth=1;
    ctx.shadowColor='rgba(0,0,0,.8)';
    ctx.shadowBlur=12;
    ctx.shadowOffsetX=-3;
    ctx.stroke();
    ctx.shadowBlur=0; ctx.shadowOffsetX=0;

    // ── Filaments/fibres arrachées ──
    // Petites lignes noires qui partent des bords comme des fibres de papier
    for(let i=0;i<leftPts.length;i+=3){
      if(Math.sin(i*7.3)>.4){
        const p=leftPts[i];
        const angle=Math.sin(i*2.1+frameCount*.05)*0.8;
        const len=4+Math.sin(i*3.7)*8;
        ctx.beginPath();
        ctx.moveTo(p.x,p.y);
        ctx.lineTo(p.x+Math.cos(angle)*len, p.y+Math.sin(angle)*len);
        ctx.strokeStyle=`rgba(0,0,0,${.4+Math.sin(i*.9)*.3})`;
        ctx.lineWidth=.4+Math.random()*.3;
        ctx.stroke();
      }
    }
    for(let i=0;i<rightPts.length;i+=3){
      if(Math.cos(i*5.1)>.4){
        const p=rightPts[i];
        const angle=Math.PI+Math.sin(i*2.4+frameCount*.05)*0.8;
        const len=4+Math.cos(i*3.2)*8;
        ctx.beginPath();
        ctx.moveTo(p.x,p.y);
        ctx.lineTo(p.x+Math.cos(angle)*len, p.y+Math.sin(angle)*len);
        ctx.strokeStyle=`rgba(0,0,0,${.4+Math.cos(i*.7)*.3})`;
        ctx.lineWidth=.4+Math.random()*.2;
        ctx.stroke();
      }
    }

    // ── Lueur orange dans le trou (aube qui perce) ──
    // Le trou laisse entrevoir une lumière chaude
    if(trail.length>3){
      const headX=trail[0].x, headY=trail[0].y;
      const glowG=ctx.createRadialGradient(headX,headY,0,headX,headY,30);
      glowG.addColorStop(0,'rgba(255,140,40,.35)');
      glowG.addColorStop(.4,'rgba(255,80,10,.12)');
      glowG.addColorStop(1,'rgba(255,40,0,0)');
      ctx.globalCompositeOperation='source-atop';
      ctx.beginPath(); ctx.arc(headX,headY,30,0,Math.PI*2);
      ctx.fillStyle=glowG; ctx.fill();
      ctx.globalCompositeOperation='source-over';
    }

    ctx.restore();

    // ── Débris qui tombent ──
    if(mouse.active && frameCount%4===0 && trail.length>0){
      spawnDebris(trail[0].x, trail[0].y);
    }
    debris=debris.filter(d=>d.life>0);
    debris.forEach(d=>{
      d.x+=d.vx; d.y+=d.vy; d.vy+=.08; d.vx*=.98; d.life-=d.decay;
      ctx.beginPath(); ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
      ctx.fillStyle=d.dark
        ? `rgba(0,0,0,${d.life*.8})`
        : `rgba(180,100,40,${d.life*.6})`;
      ctx.fill();
    });

    requestAnimationFrame(draw);
  }

  window.addEventListener('resize',()=>{ resize(); });
  resize();
  draw();
})();