<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\ConfirmationMail;
use App\Mail\OnboardingMail;
use Laravel\Cashier\Cashier;           // ← facade Cashier officielle
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class InscriptionController extends Controller
{
    // ── Afficher le formulaire ─────────────────────────────────────
    public function show(Request $request)
    {
        $choix = $request->get('choix', 'regard');
        return view('inscription.form', compact('choix'));
    }

    // ── Traiter le formulaire + créer le User + rediriger paiement ─
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'prenom'           => 'required|string|max:100',
            'nom'              => 'required|string|max:100',
            'email'            => 'required|email|max:255|unique:users,email',
            'adresse_complete' => 'required|string|max:500',
            'telephone'        => 'required|string|max:30',
            'whatsapp'         => 'nullable|string|max:30',
            'source'           => 'nullable|string|max:50',
            'traversee'        => 'required|in:regard,presence,absolu',
            'methode_paiement' => 'required|in:stripe,paypal',
            'fraction'         => 'nullable|in:comptant,2x,3x,acompte',
        ]);

        $traverseeData = User::TRAVERSEES[$validated['traversee']];

        // Créer le compte User (statut pending)
        $user = User::create([
            'prenom'           => $validated['prenom'],
            'nom'              => $validated['nom'],
            'name'             => $validated['prenom'] . ' ' . $validated['nom'],
            'email'            => $validated['email'],
            'adresse_complete' => $validated['adresse_complete'],
            'telephone'        => $validated['telephone'],
            'whatsapp'         => $validated['whatsapp'] ?? null,
            'source'           => $validated['source'] ?? null,
            'traversee'        => $validated['traversee'],
            'traversee_label'  => $traverseeData['label'],
            'traversee_prix'   => $traverseeData['prix'],
            'methode'          => $validated['methode_paiement'],
            'fraction'         => $validated['fraction'] ?? 'comptant',
            'statut'           => 'pending',
            'role'             => 'nomade',
            'password'         => Hash::make(str()->random(32)),
        ]);

        // Créer le customer Stripe immédiatement
        // (doc : createOrGetStripeCustomer)
        $user->createOrGetStripeCustomer();

        if ($validated['methode_paiement'] === 'stripe') {
            return $this->stripeCheckout($user);
        }

        return $this->paypalCheckout($user);
    }

    // ── STRIPE — via Cashier (méthode officielle doc) ──────────────
    private function stripeCheckout(User $user)
    {
        $traversee = $user->traversee;
        $fraction  = $user->fraction;
        $data      = User::TRAVERSEES[$traversee];

        // ── Paiement unique (comptant ou acompte) ──────────────────
        // doc : $user->checkout([$priceId => $qty], [...])
        if (in_array($fraction, ['comptant', 'acompte'])) {

            $montant = $fraction === 'acompte' ? 100000 : ($data['montant'] * 100);
            $label   = $fraction === 'acompte'
                ? $data['label'] . ' — Acompte (1 000 €)'
                : $data['label'];

            // On passe par checkout() avec price_data inline
            // car les prix sont dynamiques (pas forcément dans le dashboard)
            $session = $user->checkout([], [
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name'        => 'Renaît-Sens — ' . $label,
                            'description' => $fraction === 'acompte'
                                ? 'Acompte de réservation. Solde : 3 × 1 000 € par échéancier.'
                                : $data['tag'],
                        ],
                        'unit_amount' => $montant,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('inscription.success')
                    . '?session_id={CHECKOUT_SESSION_ID}&user=' . $user->id,
                'cancel_url'  => route('inscription.cancel') . '?user=' . $user->id,
                'metadata'    => [
                    'user_id'   => $user->id,
                    'traversee' => $traversee,
                    'fraction'  => $fraction,
                ],
                'locale'           => 'fr',
                // Facture automatique Stripe avec mention légale
                'invoice_creation' => [
                    'enabled'      => true,
                    'invoice_data' => [
                        'footer'   => 'TVA non applicable — Art. 293B du CGI',
                        'metadata' => ['user_id' => $user->id],
                    ],
                ],
            ]);

            return response()->json(['url' => $session->url]);
        }

        // ── Abonnement 2x ou 3x ────────────────────────────────────
        // doc : $user->newSubscription('default', $priceId)->checkout([...])
        $unitAmount    = $fraction === '2x' ? 70000 : 46700;
        $intervalCount = $fraction === '2x' ? 2 : 3;
        $label         = $data['label'] . ' — ' . $fraction;

        // On crée un Price dynamique via Stripe SDK (Cashier::stripe())
        $price = Cashier::stripe()->prices->create([
            'currency'    => 'eur',
            'unit_amount' => $unitAmount,
            'recurring'   => [
                'interval'       => 'month',
                'interval_count' => 1,
            ],
            'product_data' => ['name' => 'Renaît-Sens — ' . $label],
        ]);

        $session = $user->newSubscription('default', $price->id)
            ->checkout([
                'success_url' => route('inscription.success')
                    . '?session_id={CHECKOUT_SESSION_ID}&user=' . $user->id,
                'cancel_url'  => route('inscription.cancel') . '?user=' . $user->id,
                'metadata'    => [
                    'user_id'        => $user->id,
                    'traversee'      => $traversee,
                    'fraction'       => $fraction,
                    'interval_count' => $intervalCount,
                ],
                'locale' => 'fr',
            ]);

        return response()->json(['url' => $session->url]);
    }

    // ── PAYPAL — via srmklive ──────────────────────────────────────
    private function paypalCheckout(User $user)
    {
        $data = User::TRAVERSEES[$user->traversee];

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => (string) $user->id,
                'description'  => 'Renaît-Sens — ' . $data['label'],
                'amount' => [
                    'currency_code' => 'EUR',
                    'value'         => number_format($data['montant'], 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url'   => route('inscription.paypal.success') . '?user=' . $user->id,
                'cancel_url'   => route('inscription.cancel') . '?user=' . $user->id,
                'brand_name'   => 'Renaît-Sens',
                'locale'       => 'fr-FR',
                'landing_page' => 'BILLING',
                'user_action'  => 'PAY_NOW',
            ],
        ]);

        $approveUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approveUrl) {
            return response()->json([
                'message' => 'Erreur PayPal. Veuillez utiliser Stripe.'
            ], 500);
        }

        $user->update(['paypal_order_id' => $order['id']]);

        return response()->json(['url' => $approveUrl]);
    }

    // ── SUCCESS STRIPE ─────────────────────────────────────────────
    // doc : Cashier::stripe()->checkout->sessions->retrieve($sessionId)
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $userId    = $request->get('user');

        try {
            // Méthode officielle Cashier pour récupérer la session
            $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);
            $user    = User::findOrFail($userId);

            if ($session->payment_status === 'paid') {
                $user->update([
                    'statut'  => 'paye',
                    'paid_at' => now(),
                ]);

                Mail::to($user->email)->send(new ConfirmationMail($user));
                Mail::to($user->email)->queue(new OnboardingMail($user));
            }
        } catch (\Exception $e) {
            Log::error('Stripe success error: ' . $e->getMessage());
        }

        return view('inscription.success', ['user' => $user ?? null]);
    }

    // ── SUCCESS PAYPAL ─────────────────────────────────────────────
    public function paypalSuccess(Request $request)
    {
        $userId = $request->get('user');
        $token  = $request->get('token');
        $user   = User::findOrFail($userId);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($token);

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                $user->update(['statut' => 'paye', 'paid_at' => now()]);

                Mail::to($user->email)->send(new ConfirmationMail($user));
                Mail::to($user->email)->queue(new OnboardingMail($user));
            }
        } catch (\Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage());
        }

        return view('inscription.success', ['user' => $user]);
    }

    // ── CANCEL ─────────────────────────────────────────────────────
    public function cancel(Request $request)
    {
        $user = User::find($request->get('user'));
        if ($user && $user->statut === 'pending') {
            $user->update(['statut' => 'annule']);
        }
        return view('inscription.cancel');
    }

    // ── WEBHOOK STRIPE ─────────────────────────────────────────────
    // doc : Cashier gère automatiquement les webhooks via son propre
    // WebhookController. On définit juste les handlers custom ici.
    // Route : POST /stripe/webhook → \Laravel\Cashier\Http\Controllers\WebhookController
    //
    // Pour les événements custom, créer un listener :
    // php artisan make:listener HandleStripeCheckoutCompleted
    //
    // Sinon, route manuelle ci-dessous pour contrôle total :
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        // doc : STRIPE_WEBHOOK_SECRET dans .env
        $secret = config('cashier.webhook.secret');

        try {
            // Cashier::stripe() expose le SDK Stripe natif
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Exception $e) {
            Log::warning('Webhook invalide : ' . $e->getMessage());
            return response('Webhook Error', 400);
        }

        match ($event->type) {
            'checkout.session.completed'  => $this->handleCheckoutComplete($event->data->object),
            'invoice.payment_succeeded'   => $this->handleInvoiceSucceeded($event->data->object),
            'invoice.payment_failed'      => $this->handleInvoiceFailed($event->data->object),
            // Abonnement annulé (2x/3x terminé)
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            default => null,
        };

        return response('OK', 200);
    }

    private function handleCheckoutComplete($session): void
    {
        $userId = $session->metadata->user_id ?? null;
        if (! $userId) return;

        User::where('id', $userId)
            ->where('statut', 'pending')
            ->update(['statut' => 'paye', 'paid_at' => now()]);
    }

    private function handleInvoiceSucceeded($invoice): void
    {
        Log::info('Paiement récurrent OK : ' . $invoice->id . ' — ' . $invoice->customer_email);
        // TODO : compter les invoices et annuler l'abonnement après intervalCount paiements
    }

    private function handleInvoiceFailed($invoice): void
    {
        Log::warning('Paiement récurrent ÉCHOUÉ : ' . $invoice->id);
        // TODO : notifier l'admin + le Nomade
    }

    private function handleSubscriptionDeleted($subscription): void
    {
        Log::info('Abonnement terminé : ' . $subscription->id);
    }
}