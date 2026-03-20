<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EspoCrmService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Catalogue des traversées (source de vérité côté serveur)
    private const TRAVERSEES = [
        'regard' => [
            'label' => 'Regard',
            'prix'  => '600 €',
            'tag'   => 'Se poser, cesser de fuir et enfin se voir.',
            'feats' => '3 Dialogues à cœur ouvert|Le Lien — fil direct Sentinelle|Le Journal de Bord',
            'sens'  => 'Passer de l\'aveuglement à la clarté.',
        ],
        'presence' => [
            'label' => 'Présence',
            'prix'  => '1 000 €',
            'tag'   => 'Habiter son corps, ses sens et chaque instant.',
            'feats' => '6 Dialogues profonds|L\'Oasis — espace Nomades|L\'Ancrage — rituels sensoriels',
            'sens'  => 'Ne plus subir, mais habiter pleinement.',
        ],
        'absolu' => [
            'label' => 'Absolu',
            'prix'  => 'Prix sur demande',
            'tag'   => 'Le dépouillement total pour la renaissance ultime.',
            'feats' => 'L\'Appel du Désert — Djanet|Le Silence — 7 jours d\'immersion|La Mue — accompagnement quotidien',
            'sens'  => 'Revenir transformé à jamais.',
        ],
    ];

    public function __construct(
        private readonly EspoCrmService $espo
    ) {}

    // ─────────────────────────────────────────────────────────────────
    //  AFFICHER LE FORMULAIRE
    // ─────────────────────────────────────────────────────────────────

    public function create(): View
    {
        return view('auth.register');
    }

    // ─────────────────────────────────────────────────────────────────
    //  TRAITER L'INSCRIPTION
    // ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        // ── 1. VALIDATION ────────────────────────────────────────────
        $validated = $request->validate([

            // ── Traversée (obligatoire) ──
            'traversee'          => ['required', 'string', 'in:regard,presence,absolu'],

            // ── Champs enrichis traversée (envoyés par hidden JS) ──
            // On ne valide PAS ces champs client : on utilisera la source de vérité serveur.
            // Ils sont néanmoins lus via $request->input() ci-dessous.

            // ── Identité (obligatoires) ──
            'prenom'             => ['required', 'string', 'min:2', 'max:100'],
            'nom'                => ['required', 'string', 'min:2', 'max:100'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            // ── Téléphone ──
            'tel_prefix'         => ['nullable', 'string', 'max:10'],
            'telephone'          => ['nullable', 'string', 'max:20'],
            'telephone_full'     => ['nullable', 'string', 'max:30'],
            'tel_indicatif'      => ['nullable', 'string', 'max:10'],

            // ── Adresse ──
            'rue'                => ['nullable', 'string', 'max:255'],
            'ville'              => ['nullable', 'string', 'max:100'],
            'region'             => ['nullable', 'string', 'max:100'],
            'codepostal'         => ['nullable', 'string', 'max:20'],
            'pays'               => ['nullable', 'string', 'size:2'],
            'adresse_complete'   => ['nullable', 'string', 'max:500'],

            // ── Métadonnées JS ──
            'timezone'               => ['nullable', 'string', 'max:60'],
            'locale'                 => ['nullable', 'string', 'max:20'],
            'theme_preference'       => ['nullable', 'string', 'in:night,dawn,noon'],
            'source'                 => ['nullable', 'string', 'max:255'],
            'registered_at_client'   => ['nullable', 'string', 'max:50'],

            // ── Pacte de l'Aman ──
            'pacte_aman_check'   => ['required', 'accepted'],
        ], [
            'traversee.required'        => 'Choisis ta Traversée avant de rejoindre la Caravane.',
            'traversee.in'              => 'La Traversée choisie n\'est pas valide.',
            'prenom.required'           => 'Ton prénom est obligatoire.',
            'prenom.min'                => 'Ton prénom doit contenir au moins :min caractères.',
            'nom.required'              => 'Ton nom de famille est obligatoire.',
            'nom.min'                   => 'Ton nom doit contenir au moins :min caractères.',
            'email.required'            => 'Une adresse e-mail est requise.',
            'email.email'               => 'Cette adresse e-mail ne semble pas valide.',
            'email.unique'              => 'Cette adresse e-mail est déjà utilisée par un autre Nomade.',
            'pays.size'                 => 'Le code pays doit être au format ISO (2 lettres).',
            'pacte_aman_check.required' => 'Tu dois accepter le Pacte de l\'Aman pour rejoindre la Caravane.',
            'pacte_aman_check.accepted' => 'Tu dois accepter le Pacte de l\'Aman pour rejoindre la Caravane.',
        ]);

        // ── 2. NORMALISATION ─────────────────────────────────────────
        $prenom = ucfirst(mb_strtolower(trim($validated['prenom'])));
        $nom    = mb_strtoupper(trim($validated['nom']));
        $email  = mb_strtolower(trim($validated['email']));

        $telephoneFull = $validated['telephone_full'] ?? null;
        if (empty($telephoneFull) && ! empty($validated['tel_prefix']) && ! empty($validated['telephone'])) {
            $telephoneFull = $validated['tel_prefix'] . ' ' . $validated['telephone'];
        }

        // ── 3. DONNÉES TRAVERSÉE depuis la source de vérité serveur ──
        //    On ne fait pas confiance aux hiddens JS : on relit le catalogue.
        $traverseeKey  = $validated['traversee'];
        $traverseeData = self::TRAVERSEES[$traverseeKey];

        // ── 4. CRÉATION UTILISATEUR ───────────────────────────────────
        $user = User::create([
            // Identité
            'prenom'               => $prenom,
            'nom'                  => $nom,
            'name'                 => $prenom . ' ' . $nom,
            'email'                => $email,

            // Téléphone
            'telephone'            => $telephoneFull,
            'tel_indicatif'        => $validated['tel_indicatif'] ?? $validated['tel_prefix'] ?? null,

            // Adresse
            'rue'                  => $validated['rue']              ?? null,
            'ville'                => $validated['ville']            ?? null,
            'region'               => $validated['region']           ?? null,
            'codepostal'           => $validated['codepostal']       ?? null,
            'pays'                 => $validated['pays']             ?? null,
            'adresse_complete'     => $validated['adresse_complete'] ?? null,

            // Traversée
            'traversee'            => $traverseeKey,
            'traversee_label'      => $traverseeData['label'],
            'traversee_prix'       => $traverseeData['prix'],

            // Préférences & métadonnées
            'timezone'             => $validated['timezone']            ?? 'Indian/Antananarivo',
            'locale'               => $validated['locale']              ?? app()->getLocale(),
            'theme_preference'     => $validated['theme_preference']    ?? 'night',
            'source'               => $validated['source']              ?? 'direct',
            'registered_at_client' => $validated['registered_at_client'] ?? null,

            // Pacte
            'pacte_aman_accepted'  => true,
            'pacte_aman_at'        => now(),

            // Rôle & sécurité
            'role'                 => 'nomade',
            'password'             => Hash::make(Str::random(32)),
        ]);

        // ── 5. ÉVÉNEMENT LARAVEL ──────────────────────────────────────
       

        // ── 6. PUSH VERS ESPOCRM ─────────────────────────────────────
        $this->pushToEspoCrm($user, $telephoneFull, $traverseeKey, $traverseeData, $validated);

        // ── 7. CONNEXION AUTOMATIQUE ──────────────────────────────────
       

        // ── 8. REDIRECTION ────────────────────────────────────────────
        return redirect()
            ->route('traverser')
            ->with('success', sprintf(
                'Bienvenue dans la Caravane, %s ! Ta Traversée « %s » t\'attend. 🌵',
                $prenom,
                $traverseeData['label']
            ));
    }

    // ─────────────────────────────────────────────────────────────────
    //  PUSH VERS ESPOCRM
    // ─────────────────────────────────────────────────────────────────

    /**
     * Envoie le Lead complet vers EspoCRM.
     * Les données de traversée viennent du catalogue serveur (non des hiddens JS)
     * pour garantir leur intégrité.
     * Les erreurs sont loguées mais NE BLOQUENT PAS l'inscription.
     */
    private function pushToEspoCrm(
        User   $user,
        ?string $telephoneFull,
        string  $traverseeKey,
        array   $traverseeData,
        array   $validated
    ): void {
        if (empty(config('services.espocrm.url')) || empty(config('services.espocrm.api_key'))) {
            Log::warning('[EspoCRM] Non configuré — lead non envoyé', ['user_id' => $user->id]);
            return;
        }

        $result = $this->espo->createLead([
            // Identité
            'prenom'          => $user->prenom,
            'nom'             => $user->nom,
            'email'           => $user->email,
            'telephone_full'  => $telephoneFull,

            // Adresse
            'rue'             => $user->rue,
            'ville'           => $user->ville,
            'region'          => $user->region,
            'codepostal'      => $user->codepostal,
            'pays'            => $user->pays,

            // ── Traversée (données vérifiées côté serveur) ──────────
            'traversee'          => $traverseeKey,
            'traversee_label'    => $traverseeData['label'],
            'traversee_prix'     => $traverseeData['prix'],
            'traversee_tag'      => $traverseeData['tag'],
            'traversee_feats'    => $traverseeData['feats'],
            'traversee_sens'     => $traverseeData['sens'],

            // Métadonnées
            'timezone'              => $user->timezone,
            'locale'                => $user->locale,
            'theme_preference'      => $user->theme_preference,
            'source'                => $user->source,
            'registered_at_client'  => $user->registered_at_client,
            'pacte_aman_accepted'   => $user->pacte_aman_accepted,
        ]);

        if ($result['success'] && $result['espo_id']) {
            $user->update(['espo_lead_id' => $result['espo_id']]);
            Log::info('[EspoCRM] espo_lead_id sauvegardé', [
                'user_id'    => $user->id,
                'espo_id'    => $result['espo_id'],
                'traversee'  => $traverseeKey,
            ]);
        } else {
            Log::warning('[EspoCRM] Lead non créé', [
                'user_id'   => $user->id,
                'traversee' => $traverseeKey,
                'error'     => $result['error'],
            ]);
        }
    }
}