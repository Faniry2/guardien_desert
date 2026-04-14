<?php

// app/Listeners/HandleStripeWebhook.php
// php artisan make:listener HandleStripeWebhook

namespace App\Listeners;

use App\Models\User;
use App\Mail\ConfirmationMail;
use App\Mail\OnboardingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

class HandleStripeWebhook
{
    /**
     * doc : écoute WebhookReceived dispatché par Cashier
     * pour chaque événement Stripe reçu sur /stripe/webhook
     */
    public function handle(WebhookReceived $event): void
    {
        match ($event->payload['type']) {
            // Paiement unique réussi (comptant ou acompte)
            'checkout.session.completed'     => $this->onCheckoutComplete($event->payload),

            // Paiement récurrent réussi (2x / 3x)
            'invoice.payment_succeeded'      => $this->onInvoiceSucceeded($event->payload),

            // Paiement récurrent échoué
            'invoice.payment_failed'         => $this->onInvoiceFailed($event->payload),

            // Abonnement annulé (fin de 2x/3x ou annulation manuelle)
            'customer.subscription.deleted'  => $this->onSubscriptionDeleted($event->payload),

            default => null,
        };
    }

    // ── Checkout unique payé ───────────────────────────────────────
    // Filet de sécurité si success() ne s'est pas exécuté
    private function onCheckoutComplete(array $payload): void
    {
        $session = $payload['data']['object'];
        $userId  = $session['metadata']['user_id'] ?? null;

        if (! $userId) return;

        $user = User::find($userId);
        if (! $user || $user->statut === 'paye') return;

        $user->update(['statut' => 'paye', 'paid_at' => now()]);

        Mail::to($user->email)->send(new ConfirmationMail($user));
        Mail::to($user->email)->queue(new OnboardingMail($user));
    }

    // ── Paiement récurrent réussi (2x/3x) ─────────────────────────
    private function onInvoiceSucceeded(array $payload): void
    {
        $invoice        = $payload['data']['object'];
        $subscriptionId = $invoice['subscription'] ?? null;

        if (! $subscriptionId) return;

        // Récupérer le user via stripe_id (colonne ajoutée par Cashier)
        $user = User::where('stripe_id', $invoice['customer'])->first();
        if (! $user) return;

        Log::info("Paiement récurrent OK — user {$user->id} — invoice {$invoice['id']}");

        // Compter les invoices payées de cet abonnement
        $paidInvoices = Cashier::stripe()->invoices->all([
            'subscription' => $subscriptionId,
            'status'       => 'paid',
        ]);

        $count = count($paidInvoices->data);

        // Nombre max de paiements selon la fraction
        $max = match($user->fraction) {
            '2x' => 2,
            '3x' => 3,
            default => null,
        };

        // Annuler l'abonnement après le dernier paiement
        // doc : $user->subscription('default')->cancelNow()
        if ($max && $count >= $max) {
            $user->subscription('default')->cancelNow();
            Log::info("Abonnement {$user->fraction} terminé — user {$user->id} ({$count}/{$max} paiements)");
        }
    }

    // ── Paiement récurrent échoué ──────────────────────────────────
    private function onInvoiceFailed(array $payload): void
    {
        $invoice = $payload['data']['object'];
        Log::warning("Paiement récurrent ÉCHOUÉ — {$invoice['customer_email']} — {$invoice['id']}");

        // TODO : envoyer un email d'alerte au Nomade + à l'admin
        // Mail::to($invoice['customer_email'])->send(new PaiementEchoueMail($invoice));
    }

    // ── Abonnement supprimé ────────────────────────────────────────
    private function onSubscriptionDeleted(array $payload): void
    {
        $subscription = $payload['data']['object'];
        Log::info("Abonnement supprimé — {$subscription['id']}");
    }
}