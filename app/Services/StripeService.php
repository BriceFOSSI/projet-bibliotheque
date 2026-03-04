<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Exception;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createPaymentIntent($total)
    {
        try {
            $amount = $total * 100; // Convertir en centimes

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'integration_check' => 'accept_a_payment',
                ],
            ]);

            return [
                'client_secret' => $paymentIntent->client_secret,
                'id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            throw new Exception("Erreur Stripe: " . $e->getMessage());
        }
    }

    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            return [
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'payment_method' => $paymentIntent->payment_method,
                'id' => $paymentIntent->id
            ];
        } catch (ApiErrorException $e) {
            throw new Exception("Erreur Stripe: " . $e->getMessage());
        }
    }
}