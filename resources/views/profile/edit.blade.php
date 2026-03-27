{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.espace')

@section('title', 'Paramètres')
@section('breadcrumb', 'Paramètres')

@section('content')
<div class="px-6 py-8 max-w-2xl">

    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">Paramètres</h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-8">Gérez votre compte et vos préférences.</p>

    @if(session('status') === 'profile-updated')
        <div class="bg-[#C9973A]/10 border border-[#C9973A]/25 rounded-lg px-4 py-3 mb-6">
            <p class="text-[13px] text-[#C9973A]">✓ Profil mis à jour avec succès.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        {{-- ── Identité ─────────────────────────────────── --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
            <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-5">Identité</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', auth()->user()->prenom) }}"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                    @error('prenom')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', auth()->user()->nom) }}"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                    @error('nom')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Adresse e-mail</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                @error('email')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Téléphone ────────────────────────────────── --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
            <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-5">Téléphone</h2>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Indicatif</label>
                    <input type="text" name="tel_indicatif" value="{{ old('tel_indicatif', auth()->user()->tel_indicatif) }}"
                           placeholder="+261"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
                <div class="col-span-2">
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Numéro</label>
                    <input type="text" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}"
                           placeholder="000 000 0000"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
            </div>
        </div>

        {{-- ── Adresse ──────────────────────────────────── --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
            <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-5">Adresse</h2>

            <div class="mb-4">
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Rue</label>
                <input type="text" name="rue" value="{{ old('rue', auth()->user()->rue) }}"
                       placeholder="Numéro et nom de rue"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', auth()->user()->ville) }}"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Région</label>
                    <input type="text" name="region" value="{{ old('region', auth()->user()->region) }}"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Code postal</label>
                    <input type="text" name="codepostal" value="{{ old('codepostal', auth()->user()->codepostal) }}"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Pays (code ISO)</label>
                <input type="text" name="pays" value="{{ old('pays', auth()->user()->pays) }}"
                       placeholder="MG, FR, DZ..."
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
            </div>
        </div>

        {{-- ── Traversée ────────────────────────────────── --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
            <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-2">Traversée choisie</h2>
            <p class="text-[11px] text-[#C8C0B0]/30 italic mb-5">Ce champ est informationnel — contactez-nous pour changer de traversée.</p>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Formule</label>
                    <div class="px-4 py-2.5 bg-[#C9973A]/5 border border-[#C9973A]/12 rounded-lg
                                text-[13px] text-[#C9973A]/70">
                        {{ auth()->user()->traversee_label ?? '—' }}
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Clé</label>
                    <div class="px-4 py-2.5 bg-white/[0.02] border border-[#C9973A]/8 rounded-lg
                                text-[13px] text-[#C8C0B0]/40 font-mono">
                        {{ auth()->user()->traversee ?? '—' }}
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Tarif</label>
                    <div class="px-4 py-2.5 bg-white/[0.02] border border-[#C9973A]/8 rounded-lg
                                text-[13px] text-[#C8C0B0]/40">
                        {{ auth()->user()->traversee_prix ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Préférences ──────────────────────────────── --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
            <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-5">Préférences</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Thème</label>
                    <select name="theme_preference"
                            class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                   text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors">
                        <option value="night"  {{ auth()->user()->theme_preference === 'night'  ? 'selected' : '' }}>🌙 Nuit</option>
                        <option value="dawn"   {{ auth()->user()->theme_preference === 'dawn'   ? 'selected' : '' }}>🌅 Aube</option>
                        <option value="noon"   {{ auth()->user()->theme_preference === 'noon'   ? 'selected' : '' }}>☀️ Midi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Fuseau horaire</label>
                    <input type="text" name="timezone" value="{{ old('timezone', auth()->user()->timezone) }}"
                           placeholder="Indian/Antananarivo"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Langue</label>
                <input type="text" name="locale" value="{{ old('locale', auth()->user()->locale) }}"
                       placeholder="fr-FR"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
            </div>
        </div>

        {{-- ── Métadonnées (lecture seule) ─────────────── --}}
        <div class="bg-white/[0.01] border border-white/[0.04] rounded-xl p-6 mb-6">
            <h2 class="font-serif text-[#C8C0B0]/30 text-lg font-light mb-4">Métadonnées</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[9px] tracking-widest uppercase text-[#C8C0B0]/20 mb-1">Source d'inscription</p>
                    <p class="text-[12px] text-[#C8C0B0]/35 font-mono truncate">{{ auth()->user()->source ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[9px] tracking-widest uppercase text-[#C8C0B0]/20 mb-1">Inscrit le</p>
                    <p class="text-[12px] text-[#C8C0B0]/35">{{ auth()->user()->created_at?->isoFormat('D MMMM YYYY') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- ── Bouton save ──────────────────────────────── --}}
        <div class="flex justify-end mb-8">
            <button type="submit"
                    class="px-8 py-3 bg-[#C9973A]/10 border border-[#C9973A]/25 rounded-xl
                           text-[13px] text-[#C9973A] tracking-wider hover:bg-[#C9973A]/18 transition-all">
                Sauvegarder les modifications ✦
            </button>
        </div>

    </form>

    {{-- ── Mot de passe ─────────────────────────────────── --}}
    <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-6 mb-4">
        <h2 class="font-serif text-[#E8D5A0] text-lg font-light mb-5">Changer le mot de passe</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Mot de passe actuel</label>
                <input type="password" name="current_password"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                @error('current_password')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                    @error('password')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Confirmer</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                  text-[13px] text-[#E0D5C5] outline-none focus:border-[#C9973A]/40 transition-colors" />
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#C9973A]/10 border border-[#C9973A]/25 rounded-lg
                               text-[12px] text-[#C9973A] tracking-wide hover:bg-[#C9973A]/18 transition-all">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    {{-- ── Zone dangereuse ──────────────────────────────── --}}
    <div class="bg-red-500/5 border border-red-500/15 rounded-xl p-6">
        <h2 class="font-serif text-red-400/70 text-lg font-light mb-2">Zone dangereuse</h2>
        <p class="text-[12px] text-[#C8C0B0]/35 mb-4">La suppression de votre compte est irréversible.</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <div class="mb-4">
                <label class="block text-[9px] tracking-widest uppercase text-red-400/40 mb-2">Confirmez votre mot de passe</label>
                <input type="password" name="password"
                       class="w-full bg-white/[0.03] border border-red-500/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] outline-none focus:border-red-500/30 transition-colors" />
            </div>
            <button type="submit"
                    class="px-6 py-2.5 bg-red-500/10 border border-red-500/25 rounded-lg
                           text-[12px] text-red-400/70 hover:bg-red-500/15 transition-all">
                Supprimer mon compte
            </button>
        </form>
    </div>

</div>
@endsection