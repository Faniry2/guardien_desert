window.setMode = setMode;
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
  ['dg1a','dg1b','dg2a','dg2b'].forEach((id,i)=>{
    const el=document.getElementById(id);
    if(el) el.setAttribute('stop-color',[c.du1a,c.du1b,c.du2a,c.du2b][i]);
  });
  document.getElementById('dcrest').setAttribute('stroke', c.crest);
  document.getElementById('side-icon').textContent = c.icon;
}

// ══ CANVAS ══
(function(){
  const cv=document.getElementById('bg-canvas'),ctx=cv.getContext('2d');
  let W,H,stars=[];
  function resize(){
    W=cv.width=window.innerWidth;H=cv.height=window.innerHeight;
    stars=Array.from({length:200},()=>({
      x:Math.random()*W,y:Math.random()*H*.65,
      r:Math.random()*1.1+.15,a:Math.random()*.8+.1,
      sp:Math.random()*.003+.001,ph:Math.random()*Math.PI*2
    }));
  }
  function draw(t){
    ctx.clearRect(0,0,W,H);
    const m=currentMode,sk=MODES[m].sky;
    const g=ctx.createLinearGradient(0,0,0,H);
    g.addColorStop(0,sk[0]);g.addColorStop(.45,sk[1]);g.addColorStop(1,sk[2]);
    ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
    if(m==='night'){
      stars.forEach(s=>{
        const a=s.a*(.5+.5*Math.sin(t*s.sp*1000+s.ph));
        ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
        ctx.fillStyle=`rgba(245,230,200,${a})`;ctx.fill();
      });
      const lx=W*.85,ly=H*.09,p=1+.008*Math.sin(t*.0008);
      const mg=ctx.createRadialGradient(lx,ly,0,lx,ly,26*p);
      mg.addColorStop(0,'rgba(245,230,200,.2)');mg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(lx,ly,26*p,0,Math.PI*2);ctx.fillStyle=mg;ctx.fill();
      ctx.beginPath();ctx.arc(lx,ly,10,-Math.PI*.65,Math.PI*.65);
      ctx.fillStyle='rgba(230,210,165,.12)';ctx.fill();
    }
    if(m==='dawn'){
      stars.slice(0,35).forEach(s=>{
        ctx.beginPath();ctx.arc(s.x,s.y*.35,s.r*.45,0,Math.PI*2);
        ctx.fillStyle='rgba(255,240,220,.16)';ctx.fill();
      });
      const sx=W*.22,sy=H*.82,p=1+.012*Math.sin(t*.001);
      [[150,'rgba(255,90,20,.06)'],[90,'rgba(255,160,60,.1)'],[50,'rgba(255,200,100,.15)'],[25,'rgba(255,240,190,.65)']].forEach(([r,col])=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        rg.addColorStop(0,col);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      stars.slice(0,7).forEach((s,i)=>{
        const bx=(s.x+t*(.65+i*.05)*.05)%W;
        const fl=Math.sin(t*.005+i*1.2)*s.r;
        ctx.beginPath();ctx.moveTo(bx-6,H*.22+s.y*.05+fl);
        ctx.quadraticCurveTo(bx,H*.22+s.y*.05-fl*1.2,bx+6,H*.22+s.y*.05+fl);
        ctx.strokeStyle='rgba(20,6,2,.48)';ctx.lineWidth=1.5;ctx.stroke();
      });
    }
    if(m==='noon'){
      const sx=W*.5,sy=H*.05,p=1+.007*Math.sin(t*.0015);
      [{r:130,c:'rgba(255,248,180,.055)'},{r:82,c:'rgba(255,230,120,.1)'},{r:48,c:'rgba(255,220,100,.18)'},{r:25,c:'rgba(255,252,220,.55)'}].forEach(({r,c})=>{
        const rg=ctx.createRadialGradient(sx,sy,0,sx,sy,r*p);
        rg.addColorStop(0,c);rg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(sx,sy,r*p,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();
      });
      for(let i=0;i<12;i++){
        const a=(i/12)*Math.PI*2+t*.00005,len=100+20*Math.sin(t*.0006+i*.8);
        ctx.beginPath();ctx.moveTo(sx+Math.cos(a)*27,sy+Math.sin(a)*27);
        ctx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
        ctx.strokeStyle=`rgba(255,228,140,${.055+.03*Math.sin(t*.0012+i)})`;ctx.lineWidth=1.6;ctx.stroke();
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

// ══ PASSWORD TOGGLE ══
function togglePw(id,btn){
  const i=document.getElementById(id);
  i.type=i.type==='password'?'text':'password';
  btn.textContent=i.type==='password'?'👁':'🙈';
}

// ══ VALIDATION + SUBMIT ══
function handleRegister(){
  const prenom=document.getElementById('prenom').value.trim();
  const nom=document.getElementById('nom').value.trim();
  const email=document.getElementById('email').value.trim();
  const pw=document.getElementById('pw').value;
  const pw2=document.getElementById('pw2').value;
  const aman=document.getElementById('aman').checked;

  if(!prenom||!nom){showError('Prénom et nom sont requis, Nomade.');return}
  if(!email||!email.includes('@')){showError('Une adresse e-mail valide est requise.');return}
  if(pw.length<8){showError('Ton mot de passe doit contenir au moins 8 caractères.');return}
  if(pw!==pw2){showError('Les mots de passe ne correspondent pas.');return}
  if(!aman){showError('Tu dois accepter le Pacte de l\'Aman pour rejoindre la Caravane.');return}

  document.getElementById('auth-card').innerHTML=`
    <div class="success-msg">
      <span class="success-icon">🌵</span>
      <div class="success-title">La Caravane t'accueille, ${prenom}</div>
      <p class="success-sub">
        Ton odyssée commence.<br>
        Un e-mail de confirmation t'attend dans ta dune numérique.
      </p>
    </div>`;
}

function showError(msg){
  let el=document.getElementById('form-error');
  if(!el){
    el=document.createElement('div');
    el.id='form-error';
    el.style.cssText='font-size:.85rem;font-style:italic;color:#E05555;margin-bottom:1rem;padding:.6rem 1rem;border-left:2px solid #E05555;background:rgba(224,85,85,.07);animation:fadein .3s ease';
    document.querySelector('.btn-submit').before(el);
  }
  el.textContent=msg;
  setTimeout(()=>el&&el.remove(),4000);
}

// ══ AUTO MODE ══
(function(){
  const h=new Date().getHours();
  if(h>=5&&h<9) setMode('dawn');
  else if(h>=9&&h<19) setMode('noon');
  else setMode('night');
})();