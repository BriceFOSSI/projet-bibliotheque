<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Exception;

class PayPalService
{
    protected $client;

    public function __construct()
    {
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        
        if (empty($clientId) || empty($clientSecret)) {
            throw new Exception("Les identifiants PayPal ne sont pas configurés");
        }

        $environment = env('PAYPAL_MODE') === 'live'
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($total)
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($total, 2, '.', '')
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('payment.cancel'),
                    "return_url" => route('payment.success')
                ]
            ];

            $response = $this->client->execute($request);
            
            if ($response->statusCode !== 201) {
                throw new Exception("Erreur lors de la création de la commande PayPal");
            }

            return [
                'id' => $response->result->id,
                'status' => $response->result->status,
                'links' => $response->result->links
            ];
        } catch (Exception $e) {
            throw new Exception("Erreur PayPal: " . $e->getMessage());
        }
    }

    public function captureOrder($orderId)
    {
        try {
            $request = new OrdersCaptureRequest($orderId);
            $request->prefer('return=representation');
            $response = $this->client->execute($request);

            if ($response->statusCode !== 201) {
                throw new Exception("Erreur lors de la capture du paiement PayPal");
            }

            return [
                'status' => $response->result->status,
                'payer' => $response->result->payer,
                'purchase_units' => $response->result->purchase_units,
                'id' => $response->result->id
            ];
        } catch (Exception $e) {
            throw new Exception("Erreur PayPal: " . $e->getMessage());
        }
    }
}