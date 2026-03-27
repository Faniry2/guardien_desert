<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex items-center justify-center bg-[#F2EDE4] px-4 py-10">
        <div class="w-full max-w-md">

            {{-- En-tête --}}
            <div class="text-center mb-8">
                <p class="text-[11px] tracking-[0.25em] uppercase text-[#8C6A3A] mb-3 font-medium">
                    Espace Membre
                </p>
                <h1 class="font-serif text-[#1A0F00] text-4xl font-light tracking-wide mb-2">
                    Renaît-Sens
                </h1>
                <p class="text-[15px] text-[#2C1A00] italic font-light">
                    Retrouve l'accès à ta route, Nomade.
                </p>
            </div>

            {{-- Message explicatif --}}
            <div class="bg-[#C4956A]/10 border border-[#C4956A]/25 rounded-xl px-6 py-4 mb-6">
                <p class="text-[14px] text-[#2C1A00] font-medium leading-relaxed">
                    Le désert efface parfois les traces. Indique-nous ton adresse e-mail
                    et nous t'enverrons un lien pour choisir un nouveau mot de passe.
                </p>
            </div>

            {{-- Card formulaire --}}
            <div class="bg-white/85 backdrop-blur-sm border border-[#C4956A]/30 rounded-2xl px-8 py-8">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-6">
                        <label for="email"
                               class="block text-[11px] tracking-[0.18em] uppercase text-[#2C1A00] font-semibold mb-2">
                            Adresse e-mail
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#C4956A]/60 text-sm select-none">✦</span>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email') }}"
                                   required autofocus autocomplete="email"
                                   placeholder="ton@email.com"
                                   class="w-full pl-9 pr-4 py-3 bg-[#FAF7F2] border border-[#C4956A]/25
                                          rounded-xl text-[16px] text-[#1A0F00] font-medium
                                          placeholder-[#8C7B6A]/55
                                          outline-none focus:border-[#C4956A]/70 focus:bg-white transition-all" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-[13px] text-red-500 font-medium" />
                    </div>

                    {{-- Bouton soumettre --}}
                    <button type="submit"
                            class="w-full py-3.5 bg-[#1A0F00] text-[#F2EDE4] rounded-xl
                                   text-[14px] tracking-[0.14em] uppercase font-medium
                                   hover:bg-[#2C1A00] active:scale-[0.99] transition-all
                                   shadow-md hover:shadow-lg">
                        Envoyer le lien de réinitialisation ✦
                    </button>
                </form>
            </div>

            {{-- Retour connexion --}}
            <p class="text-center mt-5 text-[13px] text-[#2C1A00] font-medium">
                Tu te souviens de ton mot de passe ?
                <a href="{{ route('login') }}"
                   class="text-[#8C6A3A] hover:text-[#1A0F00] transition-colors ml-1 italic font-semibold">
                    ← Retour à l'entrée
                </a>
            </p>

            {{-- Citation --}}
            <p class="text-center mt-4 text-[12px] text-[#5A3A10]/65 italic tracking-wide">
                « Le désert ne ment jamais. »
            </p>
        </div>
    </div>
</x-guest-layout>
