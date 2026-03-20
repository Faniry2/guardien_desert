window.setMode=setMode;
const CFG = {
    night: {
        d1a:'#1A1208', d1b:'#0A0A12', d2a:'#241A0A', d2b:'#0D0D1A',
        crest:'#C9A96E', icon:'🌙', emoji:'⏳',
        sr:'#C9A96E', dot:'#D4622A',
    },
    dawn: {
        d1a:'#5C3018', d1b:'#2A1208', d2a:'#7A4520', d2b:'#3D2010',
        crest:'#FFB464', icon:'💫', emoji:'🌅',
        sr:'#FFB464', dot:'#E05A20',
    },
    noon: {
        d1a:'#B8942A', d1b:'#7A5F18', d2a:'#C8A050', d2b:'#8A6820',
        crest:'#FFE066', icon:'🌞', emoji:'🐪',
        sr:'#C8880A', dot:'#C04A10',
    },
};

let mode = 'night';

function setMode(m) {
    mode = m;
    document.documentElement.setAttribute('data-mode', m);
    document.querySelectorAll('.sw-btn').forEach(b => b.classList.toggle('on', b.dataset.m === m));
    const c = CFG[m];
    // Dunes
    document.getElementById('d1a').setAttribute('stop-color', c.d1a);
    document.getElementById('d1b').setAttribute('stop-color', c.d1b);
    document.getElementById('d2a').setAttribute('stop-color', c.d2a);
    document.getElementById('d2b').setAttribute('stop-color', c.d2b);
    document.getElementById('crest').setAttribute('stroke', c.crest);
    // Icons
    document.getElementById('dico').textContent  = c.icon;
    document.getElementById('pemoji').textContent = c.emoji;
    // Sigil
    document.querySelectorAll('.sr').forEach(el => el.setAttribute('stroke', c.sr));
    document.getElementById('sstar').setAttribute('stroke', c.sr);
    document.getElementById('sdot').setAttribute('fill', c.dot);
}

// ── CANVAS PRINCIPAL ──
const cv = document.getElementById('sky-canvas');
const cx = cv.getContext('2d');
let W, H, stars = [], raf;

function resize() {
    W = cv.width = window.innerWidth;
    H = cv.height = window.innerHeight;
    stars = Array.from({length:260}, () => ({
        x: Math.random()*W, y: Math.random()*H*.72,
        r: Math.random()*1.2+.2, a: Math.random()*.8+.1,
        sp: Math.random()*.003+.001, ph: Math.random()*Math.PI*2,
    }));
}

function draw(t) {
    cx.clearRect(0,0,W,H);

    if (mode === 'night') {
        stars.forEach(s => {
            const a = s.a * (.55 + .45*Math.sin(t*s.sp*1000+s.ph));
            cx.beginPath(); cx.arc(s.x,s.y,s.r,0,Math.PI*2);
            cx.fillStyle = `rgba(245,230,200,${a})`; cx.fill();
        });
        // Moon
        const lx=W*.82, ly=H*.12, lr=29;
        const mg = cx.createRadialGradient(lx,ly,0,lx,ly,lr);
        mg.addColorStop(0,'rgba(245,230,200,.2)'); mg.addColorStop(1,'transparent');
        cx.beginPath(); cx.arc(lx,ly,lr,0,Math.PI*2); cx.fillStyle=mg; cx.fill();
        cx.beginPath(); cx.arc(lx,ly,13,-Math.PI*.65,Math.PI*.65);
        cx.fillStyle='rgba(230,210,165,.14)'; cx.fill();
    }

    if (mode === 'dawn') {
        // Residual stars
        stars.slice(0,55).forEach(s => {
            cx.beginPath(); cx.arc(s.x, s.y*.5, s.r*.65, 0, Math.PI*2);
            cx.fillStyle='rgba(255,240,220,.22)'; cx.fill();
        });
        // Rising sun
        const sx=W*.36, sy=H*.63, p=1+.012*Math.sin(t*.001);
        [['rgba(255,90,20,.07)',180],['rgba(255,160,60,.11)',110],['rgba(255,210,110,.17)',65]].forEach(([c,r])=>{
            const g=cx.createRadialGradient(sx,sy,0,sx,sy,r*p);
            g.addColorStop(0,c); g.addColorStop(1,'transparent');
            cx.beginPath(); cx.arc(sx,sy,r*p,0,Math.PI*2); cx.fillStyle=g; cx.fill();
        });
        const sg=cx.createRadialGradient(sx,sy,0,sx,sy,26*p);
        sg.addColorStop(0,'rgba(255,240,200,.9)'); sg.addColorStop(.5,'rgba(255,150,50,.65)'); sg.addColorStop(1,'rgba(255,80,20,0)');
        cx.beginPath(); cx.arc(sx,sy,26*p,0,Math.PI*2); cx.fillStyle=sg; cx.fill();
        // Rays
        for(let i=0;i<8;i++){
            const a=(i/8)*Math.PI*2+t*.0002, len=55+18*Math.sin(t*.0008+i);
            cx.beginPath(); cx.moveTo(sx+Math.cos(a)*28,sy+Math.sin(a)*28);
            cx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
            cx.strokeStyle=`rgba(255,190,90,${.07+.04*Math.sin(t*.001+i)})`; cx.lineWidth=1.5; cx.stroke();
        }
    }

    if (mode === 'noon') {
        const sx=W*.5, sy=H*.07, p=1+.007*Math.sin(t*.0015);
        [{r:170,c:'rgba(255,248,180,.05)'},{r:105,c:'rgba(255,230,120,.1)'},{r:65,c:'rgba(255,220,100,.17)'},{r:34,c:'rgba(255,252,220,.5)'}].forEach(({r,c})=>{
            const g=cx.createRadialGradient(sx,sy,0,sx,sy,r*p);
            g.addColorStop(0,c); g.addColorStop(1,'transparent');
            cx.beginPath(); cx.arc(sx,sy,r*p,0,Math.PI*2); cx.fillStyle=g; cx.fill();
        });
        for(let i=0;i<12;i++){
            const a=(i/12)*Math.PI*2+t*.00005, len=120+28*Math.sin(t*.0006+i*.8);
            cx.beginPath(); cx.moveTo(sx+Math.cos(a)*36,sy+Math.sin(a)*36);
            cx.lineTo(sx+Math.cos(a)*len,sy+Math.sin(a)*len);
            cx.strokeStyle=`rgba(255,228,140,${.07+.04*Math.sin(t*.0012+i)})`; cx.lineWidth=2; cx.stroke();
        }
    }

    raf = requestAnimationFrame(draw);
}

// ── BIRDS CANVAS (aube) ──
const bc=document.getElementById('bird-canvas'), bx=bc.getContext('2d');
let bW,bH;
const birds=Array.from({length:12},(_,i)=>({
    x:-60-i*90, y:0, spd:.55+Math.random()*.55,
    flap:Math.random()*Math.PI*2, sz:7+Math.random()*5,
    yoff: Math.random()*140,
}));

function resizeB(){
    bW=bc.width=window.innerWidth; bH=bc.height=window.innerHeight;
    birds.forEach(b=>b.y=bH*.22+b.yoff);
}

function drawBirds(t){
    bx.clearRect(0,0,bW,bH);
    if(mode==='dawn'){
        birds.forEach(b=>{
            b.x+=b.spd; b.flap+=.055;
            if(b.x>bW+80) b.x=-80;
            const wy=Math.sin(b.flap)*b.sz*.9;
            bx.beginPath();
            bx.moveTo(b.x-b.sz,b.y+wy);
            bx.quadraticCurveTo(b.x,b.y-wy*1.3,b.x+b.sz,b.y+wy);
            bx.strokeStyle='rgba(20,6,2,.72)'; bx.lineWidth=2.2; bx.stroke();
        });
    }
    requestAnimationFrame(drawBirds);
}

// ── EMBERS ──
(()=>{
    const c=document.getElementById('embers');
    for(let i=0;i<16;i++){
        const e=document.createElement('div'); e.className='ember';
        e.style.setProperty('--d',(3+Math.random()*4)+'s');
        e.style.setProperty('--dl',(Math.random()*6)+'s');
        e.style.setProperty('--dr',((Math.random()-.5)*95)+'px');
        e.style.left=(Math.random()*100-50)+'px';
        e.style.background=Math.random()>.5?'#F07A3A':'#FFC878';
        const sz=Math.random()*3+1; e.style.width=e.style.height=sz+'px';
        c.appendChild(e);
    }
})();

// ── DUST ──
(()=>{
    const c=document.getElementById('dust');
    for(let i=0;i<18;i++){
        const e=document.createElement('div'); e.className='dp';
        e.style.setProperty('--d',(6+Math.random()*10)+'s');
        e.style.setProperty('--dl',(Math.random()*14)+'s');
        e.style.setProperty('--dy',((Math.random()-.5)*55)+'px');
        e.style.top=(20+Math.random()*60)+'%';
        const sz=Math.random()*9+3;
        e.style.width=sz+'px'; e.style.height=(sz*.38)+'px';
        c.appendChild(e);
    }
})();

// ── PARALLAX ──
window.addEventListener('mousemove',e=>{
    const x=(e.clientX/window.innerWidth-.5)*14;
    const y=(e.clientY/window.innerHeight-.5)*6;
    document.getElementById('dunes').style.transform=`translateX(${x*.3}px) translateY(${y*.2}px)`;
});

// ── MODULES STAGGER ──
document.querySelectorAll('.mod-card').forEach((c,i)=>{
    c.style.opacity='0'; c.style.transform='translateY(16px)';
    c.style.transition='opacity .6s, transform .6s, background 1.4s';
    setTimeout(()=>{ c.style.opacity='1'; c.style.transform='none'; }, 1800+i*90);
});

// ── INIT ──
window.addEventListener('resize',()=>{ resize(); resizeB(); });
resize(); resizeB();
requestAnimationFrame(draw);
requestAnimationFrame(drawBirds);

// Auto-detect heure réelle
(()=>{
    const h=new Date().getHours();
    if(h>=5&&h<9)       setMode('dawn');
    else if(h>=9&&h<19) setMode('noon');
    else                 setMode('night');
})();

// ── CANVAS FLAMMES LOGO (algorithme particules) ──
(function(){
    const fc    = document.getElementById('logo-fire-canvas');
    const fctx  = fc.getContext('2d');
    const logo  = document.getElementById('logo-wrap');

    let FW, FH, logoX, logoY, logoW, logoH;
    let fireAlpha = 1;          // opacité globale du feu
    let fireActive = true;
    const FIRE_START = 0.6;     // seconde où le feu démarre
    const FIRE_FADE  = 2.8;     // seconde où il commence à s'éteindre
    const FIRE_END   = 5.0;     // seconde où il disparaît

    function resizeFire(){
        FW = fc.width  = window.innerWidth;
        FH = fc.height = window.innerHeight;
        const rect = logo.getBoundingClientRect();
        logoX = rect.left + rect.width  / 2;
        logoY = rect.top  + rect.height / 2;
        logoW = rect.width;
        logoH = rect.height;
    }

    // ── Particule de flamme ──
    class Flame {
        constructor(baseX, baseY, w, h){
            this.reset(baseX, baseY, w, h);
        }
        reset(bx, by, w, h){
            // Naissance sur le bas/contour du logo
            const side = Math.random();
            if(side < 0.5){
                // bas du logo
                this.x = bx + (Math.random()-0.5)*w*1.1;
                this.y = by + h*0.45 + Math.random()*10;
            } else if(side < 0.75){
                // côté gauche
                this.x = bx - w*0.5 + Math.random()*12;
                this.y = by + (Math.random()-0.3)*h*0.8;
            } else {
                // côté droit
                this.x = bx + w*0.5 - Math.random()*12;
                this.y = by + (Math.random()-0.3)*h*0.8;
            }
            this.vx   = (Math.random()-0.5)*1.2;
            this.vy   = -(1.5 + Math.random()*3.5);
            this.size = 8 + Math.random()*22;
            this.life = 0;
            this.maxLife = 0.4 + Math.random()*0.55;
            this.wobble = Math.random()*Math.PI*2;
            this.wobbleSpeed = 2 + Math.random()*3;
        }
        update(dt){
            this.life += dt;
            this.wobble += this.wobbleSpeed * dt;
            this.x += this.vx + Math.sin(this.wobble)*0.8;
            this.y += this.vy;
            this.vy *= 0.985;
            this.size *= 0.992;
        }
        get progress(){ return Math.min(this.life / this.maxLife, 1); }
        get alive(){ return this.life < this.maxLife; }
        draw(ctx){
            const p = this.progress;
            const a = p < 0.15 ? p/0.15 : 1 - ((p-0.15)/0.85);
            // Couleur : cœur jaune → orange → rouge → transparent
            const r = 255;
            const g = Math.round(255 * Math.max(0, 1 - p*1.8));
            const b2 = 0;
            const grad = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size);
            grad.addColorStop(0,   `rgba(255,${Math.min(255,g+80)},0,${a*0.95})`);
            grad.addColorStop(0.35,`rgba(${r},${g},${b2},${a*0.7})`);
            grad.addColorStop(0.7, `rgba(200,${Math.round(g*0.4)},0,${a*0.35})`);
            grad.addColorStop(1,   `rgba(120,0,0,0)`);
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI*2);
            ctx.fillStyle = grad;
            ctx.fill();
        }
    }

    // ── Étincelle ──
    class Spark {
        constructor(bx, by, w, h){
            this.x  = bx + (Math.random()-0.5)*w*0.9;
            this.y  = by + (Math.random()-0.4)*h*0.6;
            this.vx = (Math.random()-0.5)*3;
            this.vy = -(2 + Math.random()*5);
            this.size = 1.5 + Math.random()*2.5;
            this.life = 0;
            this.maxLife = 0.3 + Math.random()*0.6;
            this.gravity = 0.08;
        }
        update(dt){
            this.life += dt;
            this.x  += this.vx;
            this.vy += this.gravity;
            this.y  += this.vy;
            this.vx *= 0.97;
        }
        get alive(){ return this.life < this.maxLife; }
        draw(ctx){
            const p = this.life / this.maxLife;
            const a = 1 - p;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size*(1-p*0.5), 0, Math.PI*2);
            ctx.fillStyle = `rgba(255,${Math.round(200-p*200)},0,${a})`;
            ctx.fill();
        }
    }

    let flames = [], sparks = [];
    let lastT  = null;
    let startT = null;

    function fireTick(ts){
        if(!fireActive){ fctx.clearRect(0,0,FW,FH); return; }
        if(startT === null) startT = ts;
        const elapsed = (ts - startT) / 1000;
        const dt = lastT ? Math.min((ts-lastT)/1000, 0.05) : 0.016;
        lastT = ts;

        fctx.clearRect(0,0,FW,FH);

        // Mise à jour position logo (au cas où resize)
        const rect = logo.getBoundingClientRect();
        logoX = rect.left + rect.width/2;
        logoY = rect.top  + rect.height/2;
        logoW = rect.width;
        logoH = rect.height;

        if(elapsed < FIRE_START){ requestAnimationFrame(fireTick); return; }

        // Fade out global
        if(elapsed > FIRE_FADE){
            fireAlpha = Math.max(0, 1 - (elapsed - FIRE_FADE)/(FIRE_END - FIRE_FADE));
        }
        if(elapsed > FIRE_END){ fireActive = false; fctx.clearRect(0,0,FW,FH); return; }

        // Spawn flammes
        const spawnRate = elapsed < FIRE_FADE ? 4 : 1;
        for(let i=0;i<spawnRate;i++){
            flames.push(new Flame(logoX, logoY, logoW, logoH));
        }
        // Spawn étincelles
        if(Math.random() < 0.4 && elapsed < FIRE_FADE){
            sparks.push(new Spark(logoX, logoY, logoW, logoH));
        }

        // Update & filter
        flames = flames.filter(f=>{ f.update(dt); return f.alive; });
        sparks = sparks.filter(s=>{ s.update(dt); return s.alive; });

        // Draw avec opacité globale
        fctx.save();
        fctx.globalAlpha = fireAlpha;
        fctx.globalCompositeOperation = 'lighter';
        flames.forEach(f => f.draw(fctx));
        fctx.globalCompositeOperation = 'source-over';
        sparks.forEach(s => s.draw(fctx));
        fctx.restore();

        requestAnimationFrame(fireTick);
    }

    window.addEventListener('resize', resizeFire);
    // Attendre que le logo soit rendu
    setTimeout(()=>{ resizeFire(); requestAnimationFrame(fireTick); }, 100);
})();