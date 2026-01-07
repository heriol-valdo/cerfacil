<?php
namespace Projet\Model;

use Stripe\Stripe;
use Stripe\PaymentIntent;

require_once __DIR__ . '/../../vendor/stripe/stripe-php/lib/Stripe.php';
require_once __DIR__ . '/../../vendor/stripe/stripe-php/lib/PaymentIntent.php';

class StripeHandler {

    /**
     * Initialise la clé API Stripe.
     * 
     * @param string $apiKey La clé API secrète Stripe.
     * @return void
     */
    public static function initialize(string $apiKey): void {
        Stripe::setApiKey($apiKey);
    }

    /**
     * Effectue un paiement via Stripe en créant et confirmant un PaymentIntent.
     * 
     * @param float $amount Montant total à payer (en euros).
     * @param string $currency La devise (par défaut 'eur').
     * @param array $metadata Métadonnées supplémentaires pour le paiement.
     * @return bool Retourne true si le paiement est réussi, false sinon.
     * @throws \Exception En cas d'erreur Stripe.
     */
    public static function Paiement(float $amount, string $currency = 'eur', array $metadata = []): bool {
        try {
            // Conversion de l'euro en centimes (Stripe attend le montant en centimes).
            $amountInCents = intval(round($amount * 100));

            // Créer un PaymentIntent.
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => $currency,
                'description' => 'Paiement pour un produit',
                'metadata' => $metadata,
                'payment_method_types' => ['card'],
            ]);

            // Récupérer l'ID et confirmer le PaymentIntent.
            $paymentIntentId = $paymentIntent->id;
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Confirmer le PaymentIntent (ici, on suppose une méthode de paiement par défaut a été définie côté client).
            $paymentIntent->confirm();

            // Vérifier si le statut est "succeeded".
            if ($paymentIntent->status === 'succeeded') {
                return true;
            }

            return false; // Paiement échoué si le statut n'est pas 'succeeded'.

        } catch (\Exception $e) {
            // Journalisation de l'erreur ou levée d'exception.
            throw new \Exception('Erreur lors du traitement du paiement : ' . $e->getMessage());
        }
    }
}
