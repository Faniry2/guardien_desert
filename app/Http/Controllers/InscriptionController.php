<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\ConfirmationMail;
use App\Mail\OnboardingMail;

class InscriptionController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    //  AFFICHER LE FORMULAIRE
    // ─────────────────────────────────────────────────────────────────
    public function show(Request $request)
    {
        $choix = $request->get('choix', 'regard');
        return view('inscription.form', compact('choix'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  CHECKOUT — Créer le User + rediriger vers paiement
    // ─────────────────────────────────────────────────────────────────
    public function checkout(Request $request)
    {
        $plainPassword = str()->random(12);
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
            'password'         => Hash::make($plainPassword),
        ]);

        // ✅ Connecter le user
        auth()->login($user);

        // // ✅ Maintenant Cashier peut fonctionner
        $user->createOrGetStripeCustomer();

        // // doc Cashier : createOrGetStripeCustomer()
        // // Crée le customer Stripe + stocke stripe_id en BDD
        // $user->createOrGetStripeCustomer();

        session(['temp_password_' . $user->id => $plainPassword]);

        if ($validated['methode_paiement'] === 'stripe') {
            return $this->stripeCheckout($user);
        }

        return $this->paypalCheckout($user);
    }

    // ─────────────────────────────────────────────────────────────────
    //  STRIPE — via Cashier (doc officielle)
    // ─────────────────────────────────────────────────────────────────
    private function stripeCheckout(User $user)
    {
        $traversee = $user->traversee;
        $fraction  = $user->fraction;
        $data      = User::TRAVERSEES[$traversee];
    
        // Méthodes de paiement — Card + PayPal + Apple/Google Pay
        $paymentMethods = ['card', 'paypal'];
    
        try {
    
            // ── Abonnement 2x ou 3x ──────────────────────────────────────
            if (in_array($fraction, ['2x', '3x'])) {
                $priceId = config('services.stripe.prices.' . $traversee . '_' . $fraction);
    
                $session = $user->newSubscription('default', $priceId)
                    ->checkout([
                        'payment_method_types' => $paymentMethods,
                        'success_url' => route('inscription.success')
                            . '?session_id={CHECKOUT_SESSION_ID}&user=' . $user->id,
                        'cancel_url'  => route('inscription.cancel')
                            . '?user=' . $user->id,
                        'metadata' => [
                            'user_id'   => $user->id,
                            'traversee' => $traversee,
                            'fraction'  => $fraction,
                        ],
                        'locale' => 'fr',
                    ]);
    
                return response()->json(['url' => $session->url]);
            }
    
            // ── Paiement unique — Comptant ────────────────────────────────
            if ($fraction === 'comptant') {
                $priceId = config('services.stripe.prices.' . $traversee . '_comptant');
    
                $session = $user->checkout([$priceId => 1], [
                    'payment_method_types' => $paymentMethods,
                    'success_url' => route('inscription.success')
                        . '?session_id={CHECKOUT_SESSION_ID}&user=' . $user->id,
                    'cancel_url'  => route('inscription.cancel')
                        . '?user=' . $user->id,
                    'metadata' => [
                        'user_id'   => $user->id,
                        'traversee' => $traversee,
                        'fraction'  => $fraction,
                    ],
                    'locale'           => 'fr',
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
 
        // ── Acompte 1000€ (Absolu uniquement) ────────────────────────
        if ($fraction === 'acompte') {
            $session = $user->checkoutCharge(100000, 'Renaît-Sens — Absolu (Acompte)', 1, [
                'payment_method_types' => $paymentMethods,
                'success_url' => route('inscription.success')
                    . '?session_id={CHECKOUT_SESSION_ID}&user=' . $user->id,
                'cancel_url'  => route('inscription.cancel')
                    . '?user=' . $user->id,
                'metadata' => [
                    'user_id'   => $user->id,
                    'traversee' => $traversee,
                    'fraction'  => 'acompte',
                ],
                'locale'           => 'fr',
                'invoice_creation' => [
                    'enabled'      => true,
                    'invoice_data' => [
                        'description' => 'Acompte de réservation. Solde : 3 × 1 000 € par échéancier.',
                        'footer'      => 'TVA non applicable — Art. 293B du CGI',
                        'metadata'    => ['user_id' => $user->id],
                    ],
                ],
            ]);
 
            return response()->json(['url' => $session->url]);
        }
 
        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur Stripe. Veuillez réessayer.'], 500);
        }
    }
    // ─────────────────────────────────────────────────────────────────
    //  PAYPAL — via srmklive/paypal
    // ─────────────────────────────────────────────────────────────────
    private function paypalCheckout(User $user)
    {
        $data = User::TRAVERSEES[$user->traversee];

        try {
            $provider = new \Srmklive\PayPal\Services\PayPal;
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

        } catch (\Exception $e) {
            Log::error('PayPal checkout error: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur PayPal. Veuillez réessayer.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  SUCCESS STRIPE
    //  doc : $request->user()->stripe()->checkout->sessions->retrieve()
    //  Mais ici user pas encore connecté → on utilise Cashier::stripe()
    // ─────────────────────────────────────────────────────────────────
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $userId    = $request->get('user');

        if (! $sessionId || ! $userId) {
            return redirect()->route('home');
        }

        try {
            // doc Cashier : Cashier::stripe() expose le StripeClient
            $session = \Laravel\Cashier\Cashier::stripe()
                ->checkout->sessions->retrieve($sessionId);

            $user = User::findOrFail($userId);

            if ($session->payment_status === 'paid' && $user->statut !== 'paye') {
                $user->update([
                    'statut'  => 'paye',
                    'paid_at' => now(),
                ]);
                $tempPassword = session('temp_password_' . $user->id);
                session()->forget('temp_password_' . $user->id); 
                Mail::to($user->email)->send(new ConfirmationMail($user));
                Mail::to($user->email)->queue(new OnboardingMail($user, $tempPassword));
            }

        } catch (\Exception $e) {
            Log::error('Stripe success error: ' . $e->getMessage());
        }

        return view('inscription.success', ['user' => $user ?? null]);
    }

    // ─────────────────────────────────────────────────────────────────
    //  SUCCESS PAYPAL
    // ─────────────────────────────────────────────────────────────────
    public function paypalSuccess(Request $request)
    {
        $userId = $request->get('user');
        $token  = $request->get('token');
        $user   = User::findOrFail($userId);

        try {
            $provider = new \Srmklive\PayPal\Services\PayPal;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($token);

            if (isset($result['status']) && $result['status'] === 'COMPLETED'
                && $user->statut !== 'paye') {
                $user->update(['statut' => 'paye', 'paid_at' => now()]);

                Mail::to($user->email)->send(new ConfirmationMail($user));
                Mail::to($user->email)->queue(new OnboardingMail($user));
            }

        } catch (\Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage());
        }

        return view('inscription.success', ['user' => $user]);
    }

    // ─────────────────────────────────────────────────────────────────
    //  CANCEL
    // ─────────────────────────────────────────────────────────────────
    public function cancel(Request $request)
    {
        $user = User::find($request->get('user'));
        if ($user && $user->statut === 'pending') {
            $user->update(['statut' => 'annule']);
        }
        return view('inscription.cancel');
    }

    // ─────────────────────────────────────────────────────────────────
    //  TÉLÉCHARGER UNE FACTURE PDF
    //  doc : composer require dompdf/dompdf
    //        $user->downloadInvoice($invoiceId, [...])
    // ─────────────────────────────────────────────────────────────────
    public function downloadInvoice(Request $request, string $invoiceId)
    {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor'  => 'Renaît-Sens',
            'product' => $request->user()->libelle_traversee,
            'street'  => 'Tassili n\'Ajjer',
            'footer'  => 'TVA non applicable — Art. 293B du CGI',
        ]);
    }
}