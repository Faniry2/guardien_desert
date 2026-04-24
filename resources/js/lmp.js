// ================================================================
// FLUID SIMULATION WebGL
// Code original Pavel Dobryakov — adapté pour la lampe magique
// Lampe positionnée en BAS À DROITE
// Fumée et étincelles orientées vers la GAUCHE
// ================================================================
var shouldEmitOnePuff = true;

// ── Lecture état pin au chargement ────────────────────────────
var lmpPinned = localStorage.getItem('lmp_pinned') === 'true';
var canvas = document.getElementById('lmp-canvas');
canvas.width  = canvas.clientWidth;
canvas.height = canvas.clientHeight;

// ----------------------------------------------------------------
// CONFIG
// ----------------------------------------------------------------
var config = {
    TEXTURE_DOWNSAMPLE:   1,
    DENSITY_DISSIPATION:  0.985,
    VELOCITY_DISSIPATION: 0.99,
    PRESSURE_DISSIPATION: 0.8,
    PRESSURE_ITERATIONS:  25,
    CURL:                 30,
    SPLAT_RADIUS:         0.008
};

var pointers   = [];
var splatStack = [];

// ----------------------------------------------------------------
// INIT WEBGL
// ----------------------------------------------------------------
var _ctx             = lmpGetWebGLContext(canvas);
var gl               = _ctx.gl;
var ext              = _ctx.ext;
var support_linear_float = _ctx.support_linear_float;

function lmpGetWebGLContext(canvas) {
    var params = { alpha:true, depth:false, stencil:false, antialias:false, premultipliedAlpha:true };
    var gl = canvas.getContext('webgl2', params);
    var isWebGL2 = !!gl;
    if (!isWebGL2) gl = canvas.getContext('webgl', params) || canvas.getContext('experimental-webgl', params);
    var halfFloat            = gl.getExtension('OES_texture_half_float');
    var support_linear_float = gl.getExtension('OES_texture_half_float_linear');
    if (isWebGL2) {
        gl.getExtension('EXT_color_buffer_float');
        support_linear_float = gl.getExtension('OES_texture_float_linear');
    }
    gl.clearColor(0, 0, 0, 0);
    var internalFormat   = isWebGL2 ? gl.RGBA16F : gl.RGBA;
    var internalFormatRG = isWebGL2 ? gl.RG16F   : gl.RGBA;
    var formatRG         = isWebGL2 ? gl.RG       : gl.RGBA;
    var texType          = isWebGL2 ? gl.HALF_FLOAT : halfFloat.HALF_FLOAT_OES;
    return {
        gl: gl,
        ext: { internalFormat, internalFormatRG, formatRG, texType },
        support_linear_float: support_linear_float
    };
}

function pointerPrototype() {
    this.id=-1; this.x=0; this.y=0;
    this.dx=0; this.dy=0; this.down=false; this.moved=false; this.color=[30,0,300];
}
pointers.push(new pointerPrototype());

// ----------------------------------------------------------------
// GLPROGRAM
// ----------------------------------------------------------------
var GLProgram = function() {
    function GLProgram(vs, fs) {
        if (!(this instanceof GLProgram)) throw new TypeError("Cannot call a class as a function");
        this.uniforms = {};
        this.program  = gl.createProgram();
        gl.attachShader(this.program, vs);
        gl.attachShader(this.program, fs);
        gl.linkProgram(this.program);
        if (!gl.getProgramParameter(this.program, gl.LINK_STATUS)) throw gl.getProgramInfoLog(this.program);
        var n = gl.getProgramParameter(this.program, gl.ACTIVE_UNIFORMS);
        for (var i=0;i<n;i++) {
            var name = gl.getActiveUniform(this.program, i).name;
            this.uniforms[name] = gl.getUniformLocation(this.program, name);
        }
    }
    GLProgram.prototype.bind = function() { gl.useProgram(this.program); };
    return GLProgram;
}();

function compileShader(type, source) {
    var shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) throw gl.getShaderInfoLog(shader);
    return shader;
}

// ----------------------------------------------------------------
// SHADERS
// ----------------------------------------------------------------
var baseVertexShader               = compileShader(gl.VERTEX_SHADER,   'precision highp float;precision mediump sampler2D;attribute vec2 aPosition;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform vec2 texelSize;void main(){vUv=aPosition*.5+.5;vL=vUv-vec2(texelSize.x,0.);vR=vUv+vec2(texelSize.x,0.);vT=vUv+vec2(0.,texelSize.y);vB=vUv-vec2(0.,texelSize.y);gl_Position=vec4(aPosition,0.,1.);}');
var clearShader                    = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;uniform sampler2D uTexture;uniform float value;void main(){gl_FragColor=value*texture2D(uTexture,vUv);}');
var displayShader                  = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;uniform sampler2D uTexture;void main(){gl_FragColor=texture2D(uTexture,vUv);}');
var splatShader                    = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;uniform sampler2D uTarget;uniform float aspectRatio;uniform vec3 color;uniform vec2 point;uniform float radius;void main(){vec2 p=vUv-point.xy;p.x*=aspectRatio;vec3 splat=exp(-dot(p,p)/radius)*color;vec3 base=texture2D(uTarget,vUv).xyz;gl_FragColor=vec4(base+splat,1.);}');
var advectionManualFilteringShader = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;uniform sampler2D uVelocity;uniform sampler2D uSource;uniform vec2 texelSize;uniform float dt;uniform float dissipation;vec4 bilerp(in sampler2D sam,in vec2 p){vec4 st;st.xy=floor(p-.5)+.5;st.zw=st.xy+1.;vec4 uv=st*texelSize.xyxy;vec4 a=texture2D(sam,uv.xy);vec4 b=texture2D(sam,uv.zy);vec4 c=texture2D(sam,uv.xw);vec4 d=texture2D(sam,uv.zw);vec2 f=p-st.xy;return mix(mix(a,b,f.x),mix(c,d,f.x),f.y);}void main(){vec2 coord=gl_FragCoord.xy-dt*texture2D(uVelocity,vUv).xy;gl_FragColor=dissipation*bilerp(uSource,coord);gl_FragColor.a=1.;}');
var advectionShader                = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;uniform sampler2D uVelocity;uniform sampler2D uSource;uniform vec2 texelSize;uniform float dt;uniform float dissipation;void main(){vec2 coord=vUv-dt*texture2D(uVelocity,vUv).xy*texelSize;gl_FragColor=dissipation*texture2D(uSource,coord);}');
var divergenceShader               = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform sampler2D uVelocity;vec2 sampleVelocity(in vec2 uv){vec2 m=vec2(1.);if(uv.x<0.){uv.x=0.;m.x=-1.;}if(uv.x>1.){uv.x=1.;m.x=-1.;}if(uv.y<0.){uv.y=0.;m.y=-1.;}if(uv.y>1.){uv.y=1.;m.y=-1.;}return m*texture2D(uVelocity,uv).xy;}void main(){float L=sampleVelocity(vL).x;float R=sampleVelocity(vR).x;float T=sampleVelocity(vT).y;float B=sampleVelocity(vB).y;gl_FragColor=vec4(.5*(R-L+T-B),0.,0.,1.);}');
var curlShader                     = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform sampler2D uVelocity;void main(){float L=texture2D(uVelocity,vL).y;float R=texture2D(uVelocity,vR).y;float T=texture2D(uVelocity,vT).x;float B=texture2D(uVelocity,vB).x;gl_FragColor=vec4(R-L-T+B,0.,0.,1.);}');
var vorticityShader                = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform sampler2D uVelocity;uniform sampler2D uCurl;uniform float curl;uniform float dt;void main(){float L=texture2D(uCurl,vL).y;float R=texture2D(uCurl,vR).y;float T=texture2D(uCurl,vT).x;float B=texture2D(uCurl,vB).x;float C=texture2D(uCurl,vUv).x;vec2 force=vec2(abs(T)-abs(B),abs(R)-abs(L));force*=1./length(force+.00001)*curl*C;vec2 vel=texture2D(uVelocity,vUv).xy;gl_FragColor=vec4(vel+force*dt,0.,1.);}');
var pressureShader                 = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform sampler2D uPressure;uniform sampler2D uDivergence;vec2 boundary(in vec2 uv){return min(max(uv,0.),1.);}void main(){float L=texture2D(uPressure,boundary(vL)).x;float R=texture2D(uPressure,boundary(vR)).x;float T=texture2D(uPressure,boundary(vT)).x;float B=texture2D(uPressure,boundary(vB)).x;float d=texture2D(uDivergence,vUv).x;gl_FragColor=vec4((L+R+B+T-d)*.25,0.,0.,1.);}');
var gradientSubtractShader         = compileShader(gl.FRAGMENT_SHADER,  'precision highp float;precision mediump sampler2D;varying vec2 vUv;varying vec2 vL;varying vec2 vR;varying vec2 vT;varying vec2 vB;uniform sampler2D uPressure;uniform sampler2D uVelocity;vec2 boundary(in vec2 uv){return min(max(uv,0.),1.);}void main(){float L=texture2D(uPressure,boundary(vL)).x;float R=texture2D(uPressure,boundary(vR)).x;float T=texture2D(uPressure,boundary(vT)).x;float B=texture2D(uPressure,boundary(vB)).x;vec2 vel=texture2D(uVelocity,vUv).xy;vel.xy-=vec2(R-L,T-B);gl_FragColor=vec4(vel,0.,1.);}');

// ----------------------------------------------------------------
// FRAMEBUFFERS
// ----------------------------------------------------------------
var textureWidth, textureHeight, density, velocity, divergence, curl, pressure;
initFramebuffers();

var clearProgram           = new GLProgram(baseVertexShader, clearShader);
var displayProgram         = new GLProgram(baseVertexShader, displayShader);
var splatProgram           = new GLProgram(baseVertexShader, splatShader);
var advectionProgram       = new GLProgram(baseVertexShader, support_linear_float ? advectionShader : advectionManualFilteringShader);
var divergenceProgram      = new GLProgram(baseVertexShader, divergenceShader);
var curlProgram            = new GLProgram(baseVertexShader, curlShader);
var vorticityProgram       = new GLProgram(baseVertexShader, vorticityShader);
var pressureProgram        = new GLProgram(baseVertexShader, pressureShader);
var gradienSubtractProgram = new GLProgram(baseVertexShader, gradientSubtractShader);

function initFramebuffers() {
    textureWidth  = gl.drawingBufferWidth  >> config.TEXTURE_DOWNSAMPLE;
    textureHeight = gl.drawingBufferHeight >> config.TEXTURE_DOWNSAMPLE;
    var iF=ext.internalFormat, iFRG=ext.internalFormatRG, fRG=ext.formatRG, tt=ext.texType;
    var lin = support_linear_float ? gl.LINEAR : gl.NEAREST;
    density    = createDoubleFBO(0, textureWidth, textureHeight, iF,   gl.RGBA, tt, lin);
    velocity   = createDoubleFBO(2, textureWidth, textureHeight, iFRG, fRG,     tt, lin);
    divergence = createFBO(4, textureWidth, textureHeight, iFRG, fRG, tt, gl.NEAREST);
    curl       = createFBO(5, textureWidth, textureHeight, iFRG, fRG, tt, gl.NEAREST);
    pressure   = createDoubleFBO(6, textureWidth, textureHeight, iFRG, fRG, tt, gl.NEAREST);
}

function createFBO(texId, w, h, internalFormat, format, type, param) {
    gl.activeTexture(gl.TEXTURE0 + texId);
    var texture = gl.createTexture();
    gl.bindTexture(gl.TEXTURE_2D, texture);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, param);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, param);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
    gl.texImage2D(gl.TEXTURE_2D, 0, internalFormat, w, h, 0, format, type, null);
    var fbo = gl.createFramebuffer();
    gl.bindFramebuffer(gl.FRAMEBUFFER, fbo);
    gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0);
    gl.viewport(0,0,w,h); gl.clear(gl.COLOR_BUFFER_BIT);
    return [texture, fbo, texId];
}

function createDoubleFBO(texId, w, h, internalFormat, format, type, param) {
    var fbo1 = createFBO(texId,     w, h, internalFormat, format, type, param);
    var fbo2 = createFBO(texId + 1, w, h, internalFormat, format, type, param);
    return {
        get first()  { return fbo1; },
        get second() { return fbo2; },
        swap: function() { var t=fbo1; fbo1=fbo2; fbo2=t; }
    };
}

var blit = (function() {
    gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1,-1,-1,1,1,1,1,-1]), gl.STATIC_DRAW);
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, gl.createBuffer());
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array([0,1,2,0,2,3]), gl.STATIC_DRAW);
    gl.vertexAttribPointer(0, 2, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(0);
    return function(destination) {
        gl.bindFramebuffer(gl.FRAMEBUFFER, destination);
        gl.drawElements(gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0);
    };
})();

// ----------------------------------------------------------------
// BOUCLE PRINCIPALE
// ----------------------------------------------------------------
var lastTime = Date.now();
update();

function update() {
    resizeCanvas();
    var dt = Math.min((Date.now() - lastTime) / 1000, 0.016);
    lastTime = Date.now();
    gl.viewport(0, 0, textureWidth, textureHeight);

    if (splatStack.length > 0) {
        for (var m=0; m<splatStack.pop(); m++) {
            var color = [Math.random()*10, Math.random()*10, Math.random()*10];
            splat(canvas.width*Math.random(), canvas.height*Math.random(),
                  1000*(Math.random()-.5), 1000*(Math.random()-.5), color);
        }
    }

    emitIdleTick30FPS();

    advectionProgram.bind();
    gl.uniform2f(advectionProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(advectionProgram.uniforms.uVelocity, velocity.first[2]);
    gl.uniform1i(advectionProgram.uniforms.uSource,   velocity.first[2]);
    gl.uniform1f(advectionProgram.uniforms.dt, dt);
    gl.uniform1f(advectionProgram.uniforms.dissipation, config.VELOCITY_DISSIPATION);
    blit(velocity.second[1]); velocity.swap();

    gl.uniform1i(advectionProgram.uniforms.uVelocity, velocity.first[2]);
    gl.uniform1i(advectionProgram.uniforms.uSource,   density.first[2]);
    gl.uniform1f(advectionProgram.uniforms.dissipation, config.DENSITY_DISSIPATION);
    blit(density.second[1]); density.swap();

    curlProgram.bind();
    gl.uniform2f(curlProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(curlProgram.uniforms.uVelocity, velocity.first[2]);
    blit(curl[1]);

    vorticityProgram.bind();
    gl.uniform2f(vorticityProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(vorticityProgram.uniforms.uVelocity, velocity.first[2]);
    gl.uniform1i(vorticityProgram.uniforms.uCurl,     curl[2]);
    gl.uniform1f(vorticityProgram.uniforms.curl, config.CURL);
    gl.uniform1f(vorticityProgram.uniforms.dt, dt);
    blit(velocity.second[1]); velocity.swap();

    divergenceProgram.bind();
    gl.uniform2f(divergenceProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(divergenceProgram.uniforms.uVelocity, velocity.first[2]);
    blit(divergence[1]);

    clearProgram.bind();
    var pressureTexId = pressure.first[2];
    gl.activeTexture(gl.TEXTURE0 + pressureTexId);
    gl.bindTexture(gl.TEXTURE_2D, pressure.first[0]);
    gl.uniform1i(clearProgram.uniforms.uTexture, pressureTexId);
    gl.uniform1f(clearProgram.uniforms.value, config.PRESSURE_DISSIPATION);
    blit(pressure.second[1]); pressure.swap();

    pressureProgram.bind();
    gl.uniform2f(pressureProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(pressureProgram.uniforms.uDivergence, divergence[2]);
    pressureTexId = pressure.first[2];
    gl.activeTexture(gl.TEXTURE0 + pressureTexId);
    for (var i=0; i<config.PRESSURE_ITERATIONS; i++) {
        gl.bindTexture(gl.TEXTURE_2D, pressure.first[0]);
        gl.uniform1i(pressureProgram.uniforms.uPressure, pressureTexId);
        blit(pressure.second[1]); pressure.swap();
    }

    gradienSubtractProgram.bind();
    gl.uniform2f(gradienSubtractProgram.uniforms.texelSize, 1/textureWidth, 1/textureHeight);
    gl.uniform1i(gradienSubtractProgram.uniforms.uPressure, pressure.first[2]);
    gl.uniform1i(gradienSubtractProgram.uniforms.uVelocity, velocity.first[2]);
    blit(velocity.second[1]); velocity.swap();

    gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
    displayProgram.bind();
    gl.uniform1i(displayProgram.uniforms.uTexture, density.first[2]);
    blit(null);

    requestAnimationFrame(update);
}

// ----------------------------------------------------------------
// SPLAT
// ----------------------------------------------------------------
function splat(x, y, dx, dy, color) {
    splatProgram.bind();
    gl.uniform1i(splatProgram.uniforms.uTarget, velocity.first[2]);
    gl.uniform1f(splatProgram.uniforms.aspectRatio, canvas.width / canvas.height);
    gl.uniform2f(splatProgram.uniforms.point, x / canvas.width, 1 - y / canvas.height);
    gl.uniform3f(splatProgram.uniforms.color, dx, -dy, 1.0);
    gl.uniform1f(splatProgram.uniforms.radius, config.SPLAT_RADIUS);
    blit(velocity.second[1]); velocity.swap();
    gl.uniform1i(splatProgram.uniforms.uTarget, density.first[2]);
    gl.uniform3f(splatProgram.uniforms.color, color[0] * 0.3, color[1] * 0.3, color[2] * 0.3);
    blit(density.second[1]); density.swap();
}

function resizeCanvas() {
    if (canvas.width !== canvas.clientWidth || canvas.height !== canvas.clientHeight) {
        canvas.width  = canvas.clientWidth;
        canvas.height = canvas.clientHeight;
        initFramebuffers();
    }
}

// ================================================================
// ÉMISSION FUMÉE — puff initial au chargement
// Lampe à droite → fumée part vers la GAUCHE (dx négatif)
// ================================================================
var idleFrame = 0;

function getLampBec() {
    var rect = document.getElementById('lmp-img').getBoundingClientRect();
    return {
        x: rect.left + rect.width  * 0.22,   // ← bec à gauche de l'image (côté gauche)
        y: rect.top  + rect.height * 0.36
    };
}

function emitIdleTick30FPS() {
    if (shouldEmitOnePuff) {
        idleFrame++;
        if (idleFrame > 30) {
            shouldEmitOnePuff = false;
            idleFrame = 0;
            var bec = getLampBec();
            for (var i = 0; i < 8; i++) {
                config.SPLAT_RADIUS = 0.003 + (i * 0.003);
                splat(
                    bec.x, bec.y,
                    -(Math.random() * 60 + 20),           // ← vers la gauche (négatif)
                    -(Math.random() * 90 + 60),           // ← vers le haut
                    [.15 + Math.random()*.1, .18 + Math.random()*.1, .30 + Math.random()*.15]
                );
            }
        }
        return;
    }
}

// ================================================================
// CLIC SUR LA LAMPE
// Fumée et étincelles orientées vers la GAUCHE
// ================================================================
var clickedOnce = false;

window.lmpClick = function() {
    var lamp = document.getElementById('lmp-img');
    lamp.classList.add('shaking');
    setTimeout(function() { lamp.classList.remove('shaking'); }, 450);
    document.getElementById('lmp-hint').style.opacity = '0';

    var bec = getLampBec();

    // Flash doré
    var flash = document.createElement('div');
    flash.style.cssText =
        'position:fixed;inset:0;pointer-events:none;z-index:997;' +
        'background:radial-gradient(circle at ' + bec.x + 'px ' + bec.y + 'px,' +
        'rgba(255,200,80,.75) 0%,rgba(255,140,20,.3) 15%,transparent 45%);opacity:1;';
    document.body.appendChild(flash);
    gsap.to(flash, {
        opacity: 0, duration: .5, ease: 'power2.out',
        onComplete: function() { flash.remove(); }
    });

    // Explosion fumée — vers la gauche
    for (var i = 0; i < 15; i++) {
        (function(idx) {
            setTimeout(function() {
                var dx = -(Math.random() * 500 + 50);    // ← vers la gauche (négatif)
                var dy = -(Math.random() * 350 + 150);   // ← vers le haut
                var r = .25 + Math.random() * .35;
                var g = .28 + Math.random() * .28;
                var b = .45 + Math.random() * .4;
                splat(bec.x, bec.y, dx, dy, [r, g, b]);
            }, idx * 55);
        })(i);
    }

    // Étincelles GSAP — arc vers la gauche
    for (var j = 0; j < 40; j++) {
        (function() {
            var el   = document.createElement('div');
            var size = 3 + Math.random() * 5;
            el.style.cssText =
                'position:fixed;width:' + size + 'px;height:' + size + 'px;' +
                'border-radius:50%;pointer-events:none;z-index:998;' +
                'left:' + bec.x + 'px;top:' + bec.y + 'px;' +
                'background:' + (Math.random() > .45 ? '#FF9A14' : '#C9973A') + ';';
            document.body.appendChild(el);

            // Arc centré vers la gauche : de -20° à -160° (partie gauche du cercle)
            var angle = -Math.PI / 2 - (Math.random() * Math.PI * .5) + (Math.random() - .5) * Math.PI * .4;
            var dist  = 80 + Math.random() * 220;
            var tx    = Math.cos(angle) * dist;
            var ty    = Math.sin(angle) * dist + dist * .6;

            gsap.to(el, {
                x: tx, y: ty,
                opacity: 0, scale: 0.1,
                duration: .6 + Math.random() * .8,
                ease: 'power2.out',
                delay: Math.random() * .15,
                onComplete: function() { el.remove(); }
            });
        })();
    }

    // Modal après 1.1s
    setTimeout(function() {
        document.getElementById('lmp-modal-bg').classList.add('open');
        lmpSpawnModalSparks();
    }, 1100);

    // Reset après 8s
    setTimeout(function() {
        clickedOnce  = false;
        idleFrame    = 0;
    }, 8000);
};

// ================================================================
// MODAL
// ================================================================
window.lmpClose = function() {
    document.getElementById('lmp-modal-bg').classList.remove('open');
};
window.lmpCloseBg = function(e) {
    if (e.target === document.getElementById('lmp-modal-bg')) lmpClose();
};

function lmpSpawnModalSparks() {
    var c = document.getElementById('lmp-modal-sparks');
    c.innerHTML = '';
    for (var i = 0; i < 20; i++) {
        var el = document.createElement('div');
        el.className = 'lmp-msp';
        var s = 2 + Math.random() * 4;
        el.style.cssText = [
            'left:'   + Math.random() * 100 + '%',
            'top:'    + (40 + Math.random() * 55) + '%',
            '--dx:'   + ((Math.random() - .5) * 100) + 'px',
            'animation-duration:' + (1.2 + Math.random() * 2.8) + 's',
            'animation-delay:'    + Math.random() * 2.5 + 's',
            'background:' + (Math.random() > .5 ? '#C9973A' : '#FFE080'),
            'width:'  + s + 'px',
            'height:' + s + 'px'
        ].join(';');
        c.appendChild(el);
    }
}

window.lmpFaqToggle = function(questionEl) {
    var answer = questionEl.nextElementSibling;
    answer.classList.toggle('open');
};

// ================================================================
// PIN / UNPIN
// ================================================================
window.lmpTogglePin = function() {
    lmpPinned = !lmpPinned;
    localStorage.setItem('lmp_pinned', lmpPinned ? 'true' : 'false');

    var btn   = document.getElementById('lmp-pin-btn');
    var scene = document.getElementById('lmp-scene');
    var slot  = document.getElementById('lmp-sidebar-slot');
    var label = document.getElementById('lmp-pin-label');

    btn.classList.toggle('pinned', lmpPinned);

    if (lmpPinned) {
        btn.title = 'Désépingler';
        if (label) label.textContent = 'Désépingler';
        scene.style.opacity = '0';
        scene.style.pointerEvents = 'none';
        if (slot) slot.style.display = 'flex';
    } else {
        btn.title = 'Épingler à la sidebar';
        if (label) label.textContent = 'Épingler';
        scene.style.opacity = '1';
        scene.style.pointerEvents = 'none';
        if (slot) slot.style.display = 'none';
    }
};

window.lmpUnpin = function() {
    lmpPinned = false;
    localStorage.setItem('lmp_pinned', 'false');

    var btn   = document.getElementById('lmp-pin-btn');
    var scene = document.getElementById('lmp-scene');
    var slot  = document.getElementById('lmp-sidebar-slot');
    var label = document.getElementById('lmp-pin-label');

    btn.classList.remove('pinned');
    btn.title = 'Épingler à la sidebar';
    if (label) label.textContent = 'Épingler';
    scene.style.opacity = '1';
    scene.style.pointerEvents = 'none';
    if (slot) slot.style.display = 'none';
};

// Appliquer état pin au chargement
(function() {
    if (lmpPinned) {
        var scene = document.getElementById('lmp-scene');
        var slot  = document.getElementById('lmp-sidebar-slot');
        var btn   = document.getElementById('lmp-pin-btn');
        var label = document.getElementById('lmp-pin-label');
        if (scene) { scene.style.opacity = '0'; scene.style.pointerEvents = 'none'; }
        if (slot)  slot.style.display = 'flex';
        if (btn)   { btn.classList.add('pinned'); btn.title = 'Désépingler'; }
        if (label) label.textContent = 'Désépingler';
    }
})();

// Masquer le bouton pin hors de l'espace membre
var currentPath = window.location.pathname;
var allowPin    = currentPath.includes('/espace') || currentPath.includes('/profile');
var pinBtn      = document.getElementById('lmp-pin-btn');
if (pinBtn && !allowPin) {
    pinBtn.style.display = 'none';
    lmpUnpin();
}