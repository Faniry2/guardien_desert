<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ─────────────────────────────────────────────────────────────────────
 *  EspoCrmService
 *  Gère toutes les interactions avec l'API REST d'EspoCRM.
 *
 *  Config .env :
 *    ESPOCRM_URL=https://crm.ton-domaine.com
 *    ESPOCRM_API_KEY=xxxxxxxxxxxxxxxxxxxxxxxx
 *    ESPOCRM_SECRET_KEY=          ← optionnel (HMAC)
 *    ESPOCRM_TIMEOUT=10
 * ─────────────────────────────────────────────────────────────────────
 */
class EspoCrmService
{
    private string  $baseUrl;
    private string  $apiKey;
    private ?string $secretKey;
    private int     $timeout;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('services.espocrm.url'), '/');
        $this->apiKey    = config('services.espocrm.api_key',  '');
        $this->secretKey = config('services.espocrm.secret_key') ?: null;
        $this->timeout   = (int) config('services.espocrm.timeout', 10);
    }

    // ─────────────────────────────────────────────────────────────────
    //  CRÉER UN LEAD
    // ─────────────────────────────────────────────────────────────────

    /**
     * @param  array $data  Données normalisées provenant du controller
     * @return array{success: bool, espo_id: string|null, error: string|null}
     */
    public function createLead(array $data): array
    {
        $payload = $this->buildLeadPayload($data);

        try {
            $response = $this->post('Lead', $payload);

            if ($response->successful()) {
                $body = $response->json();
                Log::info('[EspoCRM] Lead créé', [
                    'espo_id'    => $body['id'] ?? null,
                    'email'      => $data['email'] ?? null,
                    'traversee'  => $data['traversee'] ?? null,
                ]);
                return ['success' => true, 'espo_id' => $body['id'] ?? null, 'error' => null];
            }

            $this->logError('createLead', $response);
            return [
                'success' => false,
                'espo_id' => null,
                'error'   => $response->json('message') ?? 'Erreur EspoCRM ' . $response->status(),
            ];

        } catch (\Throwable $e) {
            Log::error('[EspoCRM] Exception createLead', [
                'message'   => $e->getMessage(),
                'email'     => $data['email'] ?? null,
            ]);
            return ['success' => false, 'espo_id' => null, 'error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  MISE À JOUR D'UN LEAD
    // ─────────────────────────────────────────────────────────────────

    public function updateLead(string $espoId, array $data): array
    {
        try {
            $response = $this->put("Lead/{$espoId}", $data);
            if ($response->successful()) {
                return ['success' => true, 'error' => null];
            }
            $this->logError('updateLead', $response);
            return ['success' => false, 'error' => $response->json('message') ?? 'Erreur mise à jour'];
        } catch (\Throwable $e) {
            Log::error('[EspoCRM] Exception updateLead', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  CONSTRUIRE LE PAYLOAD LEAD
    // ─────────────────────────────────────────────────────────────────

    private function buildLeadPayload(array $data): array
    {
        // ── Téléphone ──────────────────────────────────────────────
        $phoneNumberData = [];
        if (! empty($data['telephone_full'])) {
            $phoneNumberData[] = [
                'phoneNumber' => $data['telephone_full'],
                'type'        => 'Mobile',
                'primary'     => true,
            ];
        }

        // ── Description structurée ─────────────────────────────────
        $description = $this->buildDescription($data);

        // ── Source EspoCRM ─────────────────────────────────────────
        $espoSource = $this->mapSource($data['source'] ?? 'direct');

        // ── Payload ────────────────────────────────────────────────
        $payload = [
            // Identité
            'firstName'    => $data['prenom'] ?? '',
            'lastName'     => $data['nom']    ?? '',
            'emailAddress' => $data['email']  ?? '',

            // Téléphone
            'phoneNumberData' => $phoneNumberData,
            'phoneNumber'     => $data['telephone_full'] ?? null,

            // Adresse
            'addressStreet'     => $data['rue']        ?? null,
            'addressCity'       => $data['ville']      ?? null,
            'addressState'      => $data['region']     ?? null,
            'addressPostalCode' => $data['codepostal'] ?? null,
            'addressCountry'    => $data['pays']       ?? null,

            // Statut & source
            'status'      => 'New',
            'source'      => $espoSource,

            // Description complète
            'description' => $description,
        ];

        // Retirer les null / chaînes vides pour ne pas polluer EspoCRM
        return array_filter($payload, fn($v) => $v !== null && $v !== '' && $v !== []);
    }

    // ─────────────────────────────────────────────────────────────────
    //  CONSTRUIRE LA DESCRIPTION ESPOCRM
    // ─────────────────────────────────────────────────────────────────

    /**
     * Génère un bloc de description lisible dans la fiche Lead EspoCRM.
     *
     * Structure :
     *   ════ TRAVERSÉE CHOISIE ════
     *   🧭 Traversée       : Présence
     *   💡 Intention       : Habiter son corps, ses sens et chaque instant.
     *   💰 Tarif           : 1 000 €
     *   📦 Ce qui est inclus :
     *      • 6 Dialogues profonds
     *      • L'Oasis — espace Nomades
     *      • L'Ancrage — rituels sensoriels
     *   🔥 Ce que ça transforme : Ne plus subir, mais habiter pleinement.
     *
     *   ════ MÉTADONNÉES ════
     *   🎨 Thème           : Nuit
     *   🕐 Fuseau horaire  : Indian/Antananarivo
     *   🌐 Langue          : fr-MG
     *   📅 Inscription     : 2025-01-15T08:42:00.000Z
     *   🔗 Source          : direct
     *   ✅ Pacte de l'Aman : Accepté
     */
    private function buildDescription(array $data): string
    {
        $lines = [];

        // ── Bloc Traversée ─────────────────────────────────────────
        $traversee = $data['traversee'] ?? null;

        if ($traversee) {
            $lines[] = '════ TRAVERSÉE CHOISIE ════';
            $lines[] = '🧭 Traversée       : ' . ($data['traversee_label'] ?: ucfirst($traversee));

            if (! empty($data['traversee_tag'])) {
                $lines[] = '💡 Intention       : ' . $data['traversee_tag'];
            }

            if (! empty($data['traversee_prix'])) {
                $lines[] = '💰 Tarif           : ' . $data['traversee_prix'];
            }

            if (! empty($data['traversee_feats'])) {
                $feats = explode('|', $data['traversee_feats']);
                $lines[] = '📦 Ce qui est inclus :';
                foreach ($feats as $feat) {
                    $lines[] = '   • ' . trim($feat);
                }
            }

            if (! empty($data['traversee_sens'])) {
                $lines[] = '🔥 Ce que ça transforme : ' . $data['traversee_sens'];
            }

            $lines[] = '';
        }

        // ── Bloc Métadonnées ───────────────────────────────────────
        $lines[] = '════ MÉTADONNÉES ════';

        $themeLabels = ['night' => 'Nuit 🌙', 'dawn' => 'Aube 🌅', 'noon' => 'Midi 🌞'];
        if (! empty($data['theme_preference'])) {
            $lines[] = '🎨 Thème           : ' . ($themeLabels[$data['theme_preference']] ?? $data['theme_preference']);
        }

        if (! empty($data['timezone'])) {
            $lines[] = '🕐 Fuseau horaire  : ' . $data['timezone'];
        }

        if (! empty($data['locale'])) {
            $lines[] = '🌐 Langue          : ' . $data['locale'];
        }

        if (! empty($data['registered_at_client'])) {
            $lines[] = '📅 Inscription     : ' . $data['registered_at_client'];
        }

        if (! empty($data['source'])) {
            $lines[] = '🔗 Source          : ' . $data['source'];
        }

        if (! empty($data['pacte_aman_accepted'])) {
            $lines[] = '✅ Pacte de l\'Aman : Accepté';
        }

        return implode("\n", $lines);
    }

    // ─────────────────────────────────────────────────────────────────
    //  MAPPING SOURCE → VALEUR ESPOCRM
    // ─────────────────────────────────────────────────────────────────

    /**
     * Valeurs EspoCRM acceptées :
     *   Web Site | Call | Email | Partner | Public Relations | Internal | Other
     */
    private function mapSource(string $source): string
    {
        return match (true) {
            str_contains($source, 'google')   => 'Web Site',
            str_contains($source, 'facebook') => 'Web Site',
            str_contains($source, 'instagram')=> 'Web Site',
            str_contains($source, 'utm')      => 'Web Site',
            $source === 'direct'              => 'Web Site',
            $source === 'email'               => 'Email',
            $source === 'partner'             => 'Partner',
            $source === 'internal'            => 'Internal',
            default                           => 'Web Site',
        };
    }

    // ─────────────────────────────────────────────────────────────────
    //  HTTP — MÉTHODES INTERNES
    // ─────────────────────────────────────────────────────────────────

    private function post(string $path, array $payload): Response
    {
        return $this->client('POST', $path)->post($this->endpoint($path), $payload);
    }

    private function put(string $path, array $payload): Response
    {
        return $this->client('PUT', $path)->put($this->endpoint($path), $payload);
    }

    /**
     * Construit le client HTTP avec authentification API Key ou HMAC.
     */
    private function client(string $method = 'POST', string $path = ''): \Illuminate\Http\Client\PendingRequest
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        if ($this->secretKey) {
            // ── Authentification HMAC-SHA256 ──────────────────────
            $nonce     = uniqid('', true);
            $timestamp = time();
            $uriPath   = '/api/v1/' . ltrim($path, '/');
            $string    = implode(' ', [strtoupper($method), $uriPath, $timestamp, $nonce]);
            $hash      = base64_encode(hash_hmac('sha256', strtolower($string), $this->secretKey, true));
            $token     = base64_encode($this->apiKey . ':' . $hash . ':' . $nonce . ':' . $timestamp);

            $headers['X-Hmac-Authorization'] = $token;
        } else {
            // ── API Key simple ────────────────────────────────────
            $headers['X-Api-Key'] = $this->apiKey;
        }

        return Http::withHeaders($headers)
                   ->timeout($this->timeout)
                   ->retry(2, 500);
    }

    private function endpoint(string $path): string
    {
        return $this->baseUrl . '/api/v1/' . ltrim($path, '/');
    }

    private function logError(string $action, Response $response): void
    {
        Log::error("[EspoCRM] Erreur {$action}", [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
    }
}