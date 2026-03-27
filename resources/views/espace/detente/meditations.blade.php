{{-- resources/views/espace/detente/meditations.blade.php --}}
@extends('layouts.espace')
@section('title', 'Méditations guidées')
@section('breadcrumb', 'Méditations guidées')

@section('content')
<div class="px-6 py-8 max-w-3xl">

    {{-- Breadcrumb nav --}}
    <nav class="flex items-center gap-2 text-[11px] text-[#C8C0B0]/30 mb-8 tracking-wide">
        <a href="{{ route('detente.index') }}" class="hover:text-[#C9973A] transition-colors">Espace Détente</a>
        <span class="text-[#C9973A]/30">›</span>
        <span class="text-[#C9973A]/70">Méditations guidées</span>
    </nav>

    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">Méditations guidées</h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-10">
        Suivez le fil. Chaque étape vous ramène à vous-même.
    </p>

    {{-- ═══════════════════════════════════════ --}}
    {{-- FIL D'ARIANE — Guide textuel --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="mb-12" x-data="{ currentStep: 0, completed: [] }">

        <div class="flex items-center gap-3 mb-6">
            <span class="text-[10px] tracking-widest uppercase text-[#C9973A]/50">Guide de méditation</span>
            <div class="flex-1 h-px bg-[#C9973A]/8"></div>
            <span class="text-[11px] text-[#C8C0B0]/30" x-text="`${completed.length} / 6 étapes`"></span>
        </div>

        {{-- Barre de progression --}}
        <div class="h-1 bg-white/[0.04] rounded-full mb-8 overflow-hidden">
            <div class="h-full bg-[#C9973A] rounded-full transition-all duration-500"
                 :style="`width: ${Math.round((completed.length / 6) * 100)}%`"></div>
        </div>

        @php
        $steps = [
            [
                'num'     => 1,
                'icon'    => 'M12 3C7 3 3 7 3 12s4 9 9 9 9-4 9-9-4-9-9-9zm0 4v5l3 3',
                'titre'   => 'Trouver votre espace',
                'duree'   => '2 min',
                'couleur' => '#C9973A',
                'texte'   => 'Choisissez un endroit calme où vous ne serez pas dérangé·e pendant les prochaines minutes. Asseyez-vous confortablement, le dos droit mais sans rigidité. Posez vos mains sur vos genoux, paumes vers le ciel — en signe d\'ouverture.',
                'conseil' => 'Si votre esprit vagabonde, c\'est normal. Revenez simplement, sans jugement.',
            ],
            [
                'num'     => 2,
                'icon'    => 'M12 2C8 2 5 5 5 9c0 5.5 7 13 7 13s7-7.5 7-13c0-4-3-7-7-7zm0 9a2 2 0 100-4 2 2 0 000 4z',
                'titre'   => 'Ancrer le corps',
                'duree'   => '3 min',
                'couleur' => '#A07840',
                'texte'   => 'Fermez les yeux doucement. Sentez le poids de votre corps sur le siège. Sentez vos pieds sur le sol. Prenez conscience de chaque point de contact — vos cuisses, votre dos, vos mains. Vous êtes ici. Vous êtes présent·e.',
                'conseil' => 'Balayez mentalement votre corps de la tête aux pieds. Là où il y a tension, détendez sans forcer.',
            ],
            [
                'num'     => 3,
                'icon'    => 'M12 22c0-4-4-8-4-8s-4-4-4-8a8 8 0 0116 0c0 4-4 8-4 8s-4 4-4 8z',
                'titre'   => 'Le souffle comme ancre',
                'duree'   => '5 min',
                'couleur' => '#8A6830',
                'texte'   => 'Sans modifier votre respiration, observez-la simplement. L\'air qui entre — frais, léger. L\'air qui sort — chaud, libérant. Inspirez sur 4 temps. Retenez sur 2. Expirez sur 6 temps. Recommencez. Laissez le souffle devenir votre ancre dans le moment présent.',
                'conseil' => 'Si une pensée surgit, imaginez-la comme un nuage qui passe. Ne la retenez pas, ne la chassez pas.',
            ],
            [
                'num'     => 4,
                'icon'    => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10h.01M15 10h.01M9.5 15a3.5 3.5 0 006 0',
                'titre'   => 'L\'espace intérieur',
                'duree'   => '5 min',
                'couleur' => '#7A5820',
                'texte'   => 'Maintenant, portez votre attention vers l\'intérieur. Qu\'y a-t-il là, en ce moment ? Une émotion, une sensation, une couleur intérieure ? Ne cherchez pas à analyser. Observez simplement, comme un témoin bienveillant de votre propre paysage intérieur. Vous n\'êtes pas vos pensées. Vous êtes l\'espace qui les contient.',
                'conseil' => 'Si des émotions surgissent, accueillez-les. Chaque émotion est un messager — pas un ennemi.',
            ],
            [
                'num'     => 5,
                'icon'    => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                'titre'   => 'L\'intention du cœur',
                'duree'   => '3 min',
                'couleur' => '#C9973A',
                'texte'   => 'Placez doucement votre main droite sur votre cœur. Sentez sa chaleur, ses battements. Posez-vous cette question silencieusement : "De quoi ai-je besoin aujourd\'hui ?" Ne cherchez pas la réponse avec la tête. Laissez-la monter du cœur. Peut-être un mot, une image, une sensation. Accueillez ce qui vient.',
                'conseil' => 'Cette intention deviendra votre boussole pour la journée ou la session d\'écriture qui suit.',
            ],
            [
                'num'     => 6,
                'icon'    => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707',
                'titre'   => 'Revenir doucement',
                'duree'   => '2 min',
                'couleur' => '#E8C47A',
                'texte'   => 'Prenez trois respirations profondes. À chaque expiration, sentez votre conscience revenir à l\'espace qui vous entoure. Commencez à percevoir les sons autour de vous. Bougez doucement les doigts, les orteils. Ouvrez les yeux lentement, comme si vous découvriez le monde pour la première fois. Vous êtes revenu·e — plus léger·e, plus présent·e.',
                'conseil' => 'Prenez un instant pour noter dans votre carnet ce qui a émergé pendant cette méditation.',
            ],
        ];
        @endphp

        <div class="relative">
            {{-- Ligne verticale --}}
            <div class="absolute left-5 top-5 bottom-5 w-px"
                 style="background: linear-gradient(to bottom, rgba(201,151,58,0.3), rgba(201,151,58,0.05))"></div>

            <div class="space-y-3">
                @foreach($steps as $i => $step)
                    <div class="relative flex gap-5" x-data>

                        {{-- Nœud --}}
                        <div class="relative z-10 shrink-0 flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center
                                        transition-all duration-300 cursor-pointer"
                                 :class="completed.includes({{ $i }})
                                     ? 'bg-[#C9973A] border-[#C9973A]'
                                     : (currentStep === {{ $i }}
                                         ? 'bg-[#0A1018] border-[#C9973A]'
                                         : 'bg-[#0A1018] border-[#C9973A]/20')"
                                 @click="currentStep === {{ $i }} ? currentStep = -1 : currentStep = {{ $i }}">
                                <template x-if="completed.includes({{ $i }})">
                                    <svg class="w-4 h-4 fill-none stroke-[#0A1018] stroke-2" viewBox="0 0 16 16">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l4 4 6-6"/>
                                    </svg>
                                </template>
                                <template x-if="!completed.includes({{ $i }})">
                                    <span class="font-serif text-[13px] transition-colors"
                                          :class="currentStep === {{ $i }} ? 'text-[#C9973A]' : 'text-[#C8C0B0]/30'">
                                        {{ $step['num'] }}
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 pb-3">
                            {{-- Header --}}
                            <div class="flex items-center gap-3 mb-0 cursor-pointer py-2.5"
                                 @click="currentStep === {{ $i }} ? currentStep = -1 : currentStep = {{ $i }}">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-[14px] transition-colors"
                                           :class="currentStep === {{ $i }} || completed.includes({{ $i }})
                                               ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/50'">
                                            {{ $step['titre'] }}
                                        </p>
                                        <span class="text-[10px] text-[#C9973A]/40 tracking-wide">· {{ $step['duree'] }}</span>
                                    </div>
                                </div>
                                <svg class="w-3.5 h-3.5 fill-none stroke-[#C9973A]/30 stroke-[1.5] ml-auto shrink-0
                                            transition-transform duration-300"
                                     :class="currentStep === {{ $i }} ? 'rotate-180' : ''"
                                     viewBox="0 0 12 12">
                                    <path stroke-linecap="round" d="M2 4l4 4 4-4"/>
                                </svg>
                            </div>

                            {{-- Contenu déplié --}}
                            <div x-show="currentStep === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-250"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-5 mb-3">

                                <p class="text-[13px] text-[#C8C0B0]/70 leading-relaxed mb-4 font-light">
                                    {{ $step['texte'] }}
                                </p>

                                <div class="flex items-start gap-2 bg-[#C9973A]/5 border border-[#C9973A]/10
                                            rounded-lg px-4 py-3 mb-4">
                                    <svg class="w-3.5 h-3.5 fill-none stroke-[#C9973A]/60 stroke-[1.5] shrink-0 mt-0.5" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="6"/>
                                        <path stroke-linecap="round" d="M8 7v4M8 5.5v.5"/>
                                    </svg>
                                    <p class="text-[11px] text-[#C9973A]/60 italic leading-relaxed">
                                        {{ $step['conseil'] }}
                                    </p>
                                </div>

                                <button @click="if (!completed.includes({{ $i }})) completed.push({{ $i }}); currentStep = {{ $i + 1 }}"
                                        class="w-full py-2.5 bg-[#C9973A]/10 border border-[#C9973A]/25 rounded-lg
                                               text-[12px] text-[#C9973A] tracking-wider hover:bg-[#C9973A]/18 transition-all">
                                    @if($i < count($steps) - 1)
                                        Étape suivante →
                                    @else
                                        ✦ Terminer la méditation
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Message fin --}}
            <div x-show="completed.length === 6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-6 bg-[#C9973A]/8 border border-[#C9973A]/20 rounded-xl p-6 text-center">
                <p class="font-serif text-[#E8D5A0] text-xl font-light mb-2">Méditation accomplie ✦</p>
                <p class="text-[12px] text-[#C8C0B0]/45 italic mb-4">
                    Prenez un moment pour noter ce qui a émergé. Votre carnet vous attend.
                </p>
                <a href="{{ route('carnet.day', auth()->user()->carnet?->currentDayNumber() ?? 1) }}"
                   class="inline-block px-6 py-2.5 bg-[#C9973A] text-[#0A1018] rounded-lg
                          text-[12px] tracking-wider hover:bg-[#E8C47A] transition-all">
                    Ouvrir mon carnet →
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- MÉDIAS --}}
    {{-- ═══════════════════════════════════════ --}}
    @if(count($meditations) > 0)
        <div>
            <div class="flex items-center gap-3 mb-5">
                <span class="text-[10px] tracking-widest uppercase text-[#C9973A]/50">Méditations audio & vidéo</span>
                <div class="flex-1 h-px bg-[#C9973A]/8"></div>
            </div>

            <div class="space-y-3" x-data="{ active: null }">
                @foreach($meditations as $index => $med)
                    <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl overflow-hidden transition-all"
                         :class="active === {{ $index }} ? 'border-[#C9973A]/30' : 'hover:border-[#C9973A]/18'">

                        <div class="flex items-center gap-4 p-4 cursor-pointer"
                             @click="active === {{ $index }} ? active = null : active = {{ $index }}">

                            <div class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center
                                        bg-[#C9973A]/8 border border-[#C9973A]/15 transition-all"
                                 :class="active === {{ $index }} ? 'bg-[#C9973A]/15' : ''">
                                @if($med['type'] === 'video')
                                    <svg class="w-4 h-4 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 16 16">
                                        <rect x="1" y="3" width="10" height="10" rx="1.5"/>
                                        <path d="M11 6l4-2v8l-4-2V6z" fill="#C9973A" stroke="none"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 16 16">
                                        <circle cx="5" cy="12" r="2"/>
                                        <circle cx="12" cy="10" r="2"/>
                                        <path d="M7 12V5l7-2v7" stroke-linecap="round"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-[14px] text-[#E0D5C5] mb-0.5">{{ $med['title'] }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-[#C8C0B0]/35">{{ $med['duration'] }}</span>
                                    <span class="w-1 h-1 rounded-full bg-[#C8C0B0]/20"></span>
                                    <span class="text-[10px] text-[#C9973A]/50">
                                        {{ $med['type'] === 'video' ? 'Vidéo' : 'Audio' }}
                                    </span>
                                </div>
                            </div>

                            <svg class="w-4 h-4 fill-none stroke-[#C9973A]/30 stroke-[1.5] transition-transform duration-300 shrink-0"
                                 :class="active === {{ $index }} ? 'rotate-180' : ''"
                                 viewBox="0 0 16 16">
                                <path stroke-linecap="round" d="M4 6l4 4 4-4"/>
                            </svg>
                        </div>

                        <div x-show="active === {{ $index }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="px-4 pb-5 border-t border-[#C9973A]/8 pt-4">

                            @if($med['type'] === 'video')
                                <div class="rounded-xl overflow-hidden bg-black mb-3" style="aspect-ratio:16/9">
                                    @if(str_contains($med['src'], 'youtube') || str_contains($med['src'], 'youtu.be'))
                                        <iframe :src="active === {{ $index }} ? '{{ $med['embed'] ?? $med['src'] }}&autoplay=1&rel=0&modestbranding=1' : ''"
                                                class="w-full h-full" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media"
                                                allowfullscreen></iframe>
                                    @elseif(str_contains($med['src'], 'vimeo'))
                                        <iframe :src="active === {{ $index }} ? '{{ $med['embed'] ?? $med['src'] }}?autoplay=1&color=C9973A' : ''"
                                                class="w-full h-full" frameborder="0"
                                                allow="autoplay; fullscreen" allowfullscreen></iframe>
                                    @else
                                        <video controls class="w-full h-full">
                                            <source src="{{ $med['src'] }}" type="video/mp4" />
                                        </video>
                                    @endif
                                </div>
                            @else
                                <audio controls class="w-full mb-3" style="accent-color:#C9973A">
                                    <source src="{{ $med['src'] }}" type="audio/mpeg" />
                                </audio>
                            @endif

                            @if(isset($med['description']))
                                <p class="text-[12px] text-[#C8C0B0]/40 italic leading-relaxed font-serif">
                                    « {{ $med['description'] }} »
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('detente.index') }}"
           class="text-[12px] text-[#C8C0B0]/30 hover:text-[#C9973A] transition-colors">
            ← Retour à l'espace détente
        </a>
    </div>
</div>
@endsection