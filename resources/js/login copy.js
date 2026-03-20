window.switchTab =switchTab 
// ══ MODES ══
const MODES = {
  night:{du1a:'#1A1208',du1b:'#07071A',du2a:'#241A0A',du2b:'#07071A',crest:'#C9A96E',icon:'🌙',sky:['#03030F','#0A0820','#1A1230']},
  dawn: {du1a:'#5C3018',du1b:'#160B06',du2a:'#7A4520',du2b:'#160B06',crest:'#FFB464',icon:'💫',sky:['#1A0E22','#5C2D52','#E8845A']},
  noon: {du1a:'#B8942A',du1b:'#EDE8DF',du2a:'#C8A050',du2b:'#EDE8DF',crest:'#FFE066',icon:'🌞',sky:['#4A8FD4','#82BBE8','#C8E0F0']}
};
let currentMode = 'night';

function setMode(m){
  currentMode = m;
  document.documentElement.setAttribute('data-mode', m);
  document.querySelectorAll('.msw').forEach(b=>b.classList.toggle('on', b.dataset.m===m));
  const c = MODES[m];
  document.getElementById('dg1a').setAttribute('stop-color', c.du1a);
  document.getElementById('dg1b').setAttribute('stop-color', c.du1b);
  document.getElementById('dg2a').setAttribute('stop-color', c.du2a);
  document.getElementById('dg2b').setAttribute('stop-color', c.du2b);
  document.getElementById('dcrest').setAttribute('stroke', c.crest);
  document.getElementById('side-icon').textContent = c.icon;
}

// ══ CANVAS FOND ══
(function(){
  const c=document.getElementById('bg-canvas'),ctx=c.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=c.width=window.innerWidth;H=c.height=window.innerHeight;
    stars=Array.from({length:200},()=>({
      x:Math.random()*W,y:Math.random()*H*.7,
      r:Math.random()*1.1+.15,a:Math.random()*.8+.1,
      sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode, sk=MODES[m].sky;
    // Ciel
    const g=ctx.createLinearGradient(0,0,0,H);
    g.addColorStop(0,sk[0]);g.addColorStop(.45,sk[1]);g.addColorStop(1,sk[2]);
    ctx.fillStyle=g;ctx.fillRect(0,0,W,H);

    if(m==='night'){
      stars.forEach(s=>{
        const a=s.a*(.5+.5*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
        ctx.fillStyle=`rgba(245,230,200,${a})`;ctx.fill();
      });
      // Lune
      const lx=W*.85,ly=H*.1,p=1+.008*Math.sin(t*.0008);
      const mg=ctx.createRadialGradient(lx,ly,0,lx,ly,28*p);
      mg.addColorStop(0,'rgba(245,230,200,.22)');mg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(lx,ly,28*p,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
      ctx.beginPath();ctx.arc(lx,ly,11,-Math.PI*.65,Math.PI*.65);
      ctx.fillStyle='rgba(230,210,165,.13)';ctx.fill();
    }
    if(m==='dawn'){
      stars.slice(0,40).forEach(s=>{
        ctx.beginPath();ctx.arc(s.x,s.y*.4,s.r*.5,0,Math.PI*2);
        ctx.fillStyle='rgba(255,240,220,.18)';ctx.fill();
      });
      const sx=W*.25,sy=H*.78,p2=1+.012*Math.sin(t*.001);
      [[160,'rgba(255,90,20,.06)'],[95,'rgba(255,160,60,.1)'],[55,'rgba(255,200,100,.16)'],[28,'rgba(255,240,190,.7)']].forEach(([r,col])=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p2);
        rg.addColorStop(0,col);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p2,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      for(let i=0;i<7;i++){
        const a=(i/7)*Math.PI+t*.0001,len=80+20*Math.sin(t*.0008+i);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*30,sy+Math.sin(a)*30);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,180,80,${.06+.03*Math.sin(t*.001+i)})`;ctx.lineWidth=1.2;ctx.stroke();
      }
      stars.slice(0,8).forEach((s,i)=>{
        const bx=(s.x+t*(.7+i*.05)*0.05)%W;
        const fl=Math.sin(t*.005+i*1.2)*s.r*1.1;
        ctx.beginPath();
        ctx.moveTo(bx-7,H*.25+s.y*.06+fl);
        ctx.quadraticCurveTo(bx,H*.25+s.y*.06-fl*1.2,bx+7,H*.25+s.y*.06+fl);
        ctx.strokeStyle='rgba(20,6,2,.5)';ctx.lineWidth=1.6;ctx.stroke();
      });
    }
    if(m==='noon'){
      const sx=W*.5,sy=H*.06,p2=1+.007*Math.sin(t*.0015);
      [{r:140,c:'rgba(255,248,180,.06)'},{r:88,c:'rgba(255,230,120,.11)'},{r:50,c:'rgba(255,220,100,.19)'},{r:26,c:'rgba(255,252,220,.58)'}].forEach(({r,c})=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p2);
        rg.addColorStop(0,c);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p2,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      for(let i=0;i<12;i++){
        const a=(i/12)*Math.PI*2+t*.00005,len=105+22*Math.sin(t*.0006+i*.8);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*28,sy+Math.sin(a)*28);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,228,140,${.06+.04*Math.sin(t*.0012+i)})`;ctx.lineWidth=1.7;ctx.stroke();
      }
    }
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',resize);resize();
  requestAnimationFrame(draw);
})();

// ══ CURSEUR ══
const cur=document.getElementById('cur'),curR=document.getElementById('cur-r');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
(function ac(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;curR.style.left=rx+'px';curR.style.top=ry+'px';requestAnimationFrame(ac)})();

// ══ TABS ══
function switchTab(tab){
  document.querySelectorAll('.tab').forEach((t,i)=>t.classList.toggle('active', (i===0&&tab==='login')||(i===1&&tab==='register')));
  document.getElementById('panel-login').classList.toggle('active', tab==='login');
  document.getElementById('panel-register').classList.toggle('active', tab==='register');
}

// ══ PASSWORD TOGGLE ══
function togglePw(id, btn){
  const inp=document.getElementById(id);
  inp.type=inp.type==='password'?'text':'password';
  btn.textContent=inp.type==='password'?'👁':'🙈';
}

// ══ SUBMIT HANDLERS ══
function handleLogin(){
  const card=document.querySelector('#panel-login .auth-card') || document.querySelector('#panel-login');
  card.innerHTML=`<div class="success-msg">
    <span class="success-icon">⏳</span>
    <div class="success-title">Bienvenue, Nomade</div>
    <p class="success-sub">Le feu de camp t'attend.<br>Redirection vers ta tente...</p>
  </div>`;
}
function handleRegister(){
  const ok=document.getElementById('aman').checked;
  if(!ok){ alert('Tu dois accepter le Pacte de l\'Aman pour rejoindre la caravane.'); return; }
  document.getElementById('panel-register').innerHTML=`<div class="success-msg">
    <span class="success-icon">🌵</span>
    <div class="success-title">La Caravane t'accueille</div>
    <p class="success-sub">Ton odyssée commence.<br>Vérifie ton e-mail pour activer ton compte.</p>
  </div>`;
}

// ══ AUTO MODE ══
(function(){
  const h=new Date().getHours();
  if(h>=5&&h<9) setMode('dawn');
  else if(h>=9&&h<19) setMode('noon');
  else setMode('night');
})();