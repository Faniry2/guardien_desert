{{-- resources/views/partials/gourde.blade.php --}}
{{-- Usage : @include('partials.gourde', ['day' => $day, 'mod' => $mod]) --}}

@php
    $dayScore    = $day / 90 * 60;
    $modScore    = ($mod - 1) / 10 * 40;
    $waterLevel  = min(100, round($dayScore + $modScore));

    // Couleur eau : tirée de l'image — eau cristalline bleutée
    $waterColor  = $waterLevel < 30 ? '#7BBFCC' : ($waterLevel < 70 ? '#5AAABB' : '#3D96A8');
    $waterLight  = $waterLevel < 30 ? '#A8D8E4' : ($waterLevel < 70 ? '#85C8D6' : '#6CB8C8');

    $message = match(true) {
        $waterLevel === 0 => 'Ta gourde t\'attend.',
        $waterLevel < 20  => 'Premiers pas dans le désert.',
        $waterLevel < 40  => 'Tu avances, Nomade.',
        $waterLevel < 60  => 'La moitié du chemin.',
        $waterLevel < 80  => 'Le sommet approche.',
        $waterLevel < 100 => 'Tu touches à ta renaissance.',
        default            => 'Renaissance accomplie.',
    };

    $moduleNames = [1=>'Reset',2=>'Reboot',3=>'Clarté',4=>'Ancrage',5=>'Silence',
                     6=>'Vision',7=>'Lâcher-prise',8=>'Connexion',9=>'Puissance',10=>'Renaissance'];
    $moduleName  = $moduleNames[$mod] ?? 'Reset';
    $uid = 'g' . substr(md5(uniqid()), 0, 8);

    // La gourde a l'eau visible dans le ventre (bas de l'image)
    // waterHeightPct = hauteur de la zone eau visible en % de la hauteur image
    $waterHeightPct = $waterLevel * 0.38; // max 38% de l'image
    $level = round($waterLevel / 10) * 10; // arrondi au multiple de 10
    $img   = asset("images/gourdes/gourde-{$level}.png");
@endphp


<div class="flex flex-col items-center" style="width:160px">

    <style>
    @keyframes {{ $uid }}-fall {
        0%   { opacity:0;   transform:translateY(-28px) scaleY(1.2); }
        25%  { opacity:.9;  transform:translateY(-14px) scaleY(1); }
        75%  { opacity:.7; }
        100% { opacity:0;   transform:translateY(0px) scaleY(.8); }
    }
    @keyframes {{ $uid }}-splash {
        0%   { opacity:0; transform:scale(0); }
        30%  { opacity:.7; transform:scale(1); }
        100% { opacity:0; transform:scale(2.5); }
    }
    @keyframes {{ $uid }}-wave {
        0%,100% { transform:translateX(0); }
        50%     { transform:translateX(-6px); }
    }
    @keyframes {{ $uid }}-bub {
        0%   { opacity:.5; transform:translateY(0) scale(1); }
        100% { opacity:0;  transform:translateY(-22px) scale(.3); }
    }
    @keyframes {{ $uid }}-ripple {
        0%   { opacity:.6; transform:scale(.3); }
        100% { opacity:0;  transform:scale(1); }
    }
    .{{ $uid }}-d1 { animation:{{ $uid }}-fall 1.6s ease-in infinite 0s; }
    .{{ $uid }}-d2 { animation:{{ $uid }}-fall 1.6s ease-in infinite .55s; }
    .{{ $uid }}-d3 { animation:{{ $uid }}-fall 1.6s ease-in infinite 1.1s; }
    .{{ $uid }}-s1 { animation:{{ $uid }}-splash 1.6s ease-out infinite 0s; }
    .{{ $uid }}-s2 { animation:{{ $uid }}-splash 1.6s ease-out infinite .55s; }
    .{{ $uid }}-s3 { animation:{{ $uid }}-splash 1.6s ease-out infinite 1.1s; }
    .{{ $uid }}-b1 { animation:{{ $uid }}-bub 2.8s ease-out infinite 0s; }
    .{{ $uid }}-b2 { animation:{{ $uid }}-bub 2.8s ease-out infinite .95s; }
    .{{ $uid }}-b3 { animation:{{ $uid }}-bub 2.8s ease-out infinite 1.9s; }
    .{{ $uid }}-w  { animation:{{ $uid }}-wave 3.2s ease-in-out infinite; }
    .{{ $uid }}-rp { animation:{{ $uid }}-ripple 1.6s ease-out infinite; }
    </style>

    <div class="relative" style="width:145px">

        {{-- ── GOUTTES QUI TOMBENT dans le goulot ── --}}
        @if($waterLevel > 0)
        {{-- Position : centré sur le goulot de la gourde (environ 20% du haut) --}}
        <div style="position:absolute;top:3%;left:50%;transform:translateX(-50%);
                    z-index:5;display:flex;flex-direction:column;align-items:center;gap:2px">

            {{-- 3 gouttes en cascade --}}
            <div style="display:flex;gap:4px;align-items:flex-end">
                <div class="{{ $uid }}-d1" style="
                    width:5px;height:12px;
                    background:linear-gradient(180deg, {{ $waterLight }}, {{ $waterColor }});
                    border-radius:50% 50% 50% 50% / 20% 20% 80% 80%;
                    opacity:.85;"></div>
                <div class="{{ $uid }}-d2" style="
                    width:4px;height:9px;
                    background:linear-gradient(180deg, {{ $waterLight }}, {{ $waterColor }});
                    border-radius:50% 50% 50% 50% / 20% 20% 80% 80%;
                    opacity:.7;margin-bottom:3px;"></div>
                <div class="{{ $uid }}-d3" style="
                    width:4px;height:10px;
                    background:linear-gradient(180deg, {{ $waterLight }}, {{ $waterColor }});
                    border-radius:50% 50% 50% 50% / 20% 20% 80% 80%;
                    opacity:.6;"></div>
            </div>
        </div>
        @endif

        {{-- ── IMAGE GOURDE ── --}}
        

        <img src="{{ $img }}" alt="Gourde {{ $level }}%" style="width:145px;height:auto">
       

        {{-- ── ZONE EAU (superposée sur le ventre) ── --}}
        @if($waterLevel > 0)
        {{-- Le ventre de la gourde est dans la moitié basse de l'image
             On positionne l'eau depuis le bas : bottom 5% → 43% de hauteur --}}
        <div style="
            position:absolute;
            bottom:4%;
            left:12%;
            right:12%;
            height:{{ $waterHeightPct }}%;
            z-index:1;
            overflow:hidden;
            border-radius:0 0 45% 45% / 0 0 35% 35%;
        ">
            {{-- Corps de l'eau --}}
            <div style="
                position:absolute;inset:0;
                background:{{ $waterColor }};
                opacity:.45;
            "></div>

            {{-- Surface ondulée + splash impact de la goutte --}}
            <div class="{{ $uid }}-w" style="
                position:absolute;top:0;left:-10%;right:-10%;height:100%;
            ">
                <svg style="position:absolute;top:0;left:0;width:100%;height:24px;overflow:visible"
                     viewBox="0 0 120 24" preserveAspectRatio="none">
                    <path d="M0,12 Q15,5 30,12 Q45,19 60,12 Q75,5 90,12 Q105,19 120,12 L120,24 L0,24 Z"
                          fill="{{ $waterColor }}" opacity=".72"/>
                    <path d="M0,16 Q20,10 40,16 Q60,22 80,16 Q100,10 120,16 L120,24 L0,24 Z"
                          fill="{{ $waterLight }}" opacity=".3"/>
                </svg>

                {{-- Cercle de splash (impact goutte) --}}
                <div class="{{ $uid }}-rp" style="
                    position:absolute;top:4px;left:50%;transform:translateX(-50%);
                    width:18px;height:6px;
                    border:1.5px solid {{ $waterLight }};
                    border-radius:50%;opacity:0;
                "></div>
            </div>

            {{-- Reflet interne eau --}}
            <div style="
                position:absolute;top:20%;left:18%;
                width:25%;height:55%;
                background:rgba(255,255,255,.14);
                border-radius:50%;
                transform:rotate(-8deg);
            "></div>

            {{-- Bulles qui montent --}}
            @if($waterLevel > 10)
            <div class="{{ $uid }}-b1" style="
                position:absolute;bottom:25%;left:22%;
                width:5px;height:5px;
                background:rgba(255,255,255,.35);
                border-radius:50%;border:1px solid rgba(255,255,255,.2);"></div>
            <div class="{{ $uid }}-b2" style="
                position:absolute;bottom:38%;left:55%;
                width:4px;height:4px;
                background:rgba(255,255,255,.28);
                border-radius:50%;border:1px solid rgba(255,255,255,.15);"></div>
            <div class="{{ $uid }}-b3" style="
                position:absolute;bottom:18%;left:40%;
                width:3px;height:3px;
                background:rgba(255,255,255,.22);
                border-radius:50%;"></div>
            @endif
        </div>
        @endif

        {{-- Badge % --}}
        <div style="
            position:absolute;bottom:20%;left:50%;
            transform:translateX(-50%);z-index:3;
            background:rgba(0,0,0,.5);
            border:1px solid rgba({{ $waterLevel < 30 ? '123,191,204' : '90,170,187' }},.4);
            border-radius:20px;padding:2px 10px;
            backdrop-filter:blur(6px);
        ">
            <span style="font-family:Georgia,serif;font-size:13px;
                         color:#E8F8FF;white-space:nowrap;letter-spacing:.05em">
                {{ $waterLevel }}%
            </span>
        </div>
    </div>

    {{-- Texte sous la gourde --}}
    <div class="text-center mt-3">
        <p class="text-[11px] tracking-widest uppercase" style="color:{{ $waterColor }}">
            Gourde du Nomade
        </p>
        <p class="text-[10px] text-[#C8C0B0]/40 italic mt-1 leading-snug px-2">
            {{ $message }}
        </p>
    </div>
</div>