<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Achat;

class PaymentController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    /**
     * Création d'une commande PayPal
     */
    public function checkout(Request $request)
    {
        try {
            $panier = $request->panier ?? [];
            $total = array_sum(array_map(fn($item) => $item['prix'] * $item['quantite'], $panier));

            // Sauvegarder le panier en session pour l'utiliser après capture
            $request->session()->put('pending_panier', $panier);
            $request->session()->put('pending_total', $total);

            $order = $this->paypal->createOrder($total);

            return response()->json(['id' => $order['id']]);
        } catch (\Exception $e) {
            Log::error('Erreur checkout PayPal: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Capture de la commande PayPal et enregistrement en base
     */
    public function capture(Request $request)
    {
        try {
            $orderId = $request->orderID;
            $result = $this->paypal->captureOrder($orderId);

            $status = $result['status'] ?? ($result->result->status ?? null);

            if (! in_array(strtoupper($status), ['COMPLETED', 'APPROVED'])) {
                return response()->json(['error' => 'Paiement non complété', 'status' => $status], 400);
            }

            $panier = $request->session()->pull('pending_panier', []);
            $total = $request->session()->pull('pending_total', 0);

            DB::beginTransaction();

            // Créer l'achat
            $achat = Achat::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'methode_paiement' => 'paypal',
            ]);

            // Attacher les livres à l'achat
            foreach ($panier as $livreId => $item) {
                $achat->livres()->attach($livreId, [
                    'quantite' => $item['quantite'] ?? 1,
                    'prix_unitaire' => $item['prix'] ?? 0
                ]);
            }

            DB::commit();

            // Vider le panier de session
            $request->session()->forget('panier');

            return response()->json([
                'status' => $status,
                'achat_id' => $achat->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur capture PayPal: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        return redirect()->route('panier.index')->with('success', 'Paiement effectué avec succès!');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('panier.index')->with('error', 'Paiement annulé');
    }

    /**
     * Création d'un paiement Stripe
     */
    public function createStripePaymentIntent(Request $request)
    {
        try {
            $panier = $request->panier ?? [];
            $total = array_sum(array_map(fn($item) => $item['prix'] * $item['quantite'], $panier));

            $paymentIntent = $this->stripe->createPaymentIntent($total);

            return response()->json(['client_secret' => $paymentIntent['client_secret']]);
        } catch (\Exception $e) {
            Log::error('Erreur création Payment Intent Stripe: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirmation d'un paiement Stripe et enregistrement en base
     */
    public function confirmStripePayment(Request $request)
    {
        try {
            $paymentIntentId = $request->payment_intent_id;
            $result = $this->stripe->retrievePaymentIntent($paymentIntentId);

            $status = $result['status'] ?? null;

            if (! in_array(strtoupper($status), ['SUCCEEDED'])) {
                return response()->json(['error' => 'Paiement non complété', 'status' => $status], 400);
            }

            $panier = $request->session()->pull('pending_panier', []);
            $total = $request->session()->pull('pending_total', 0);

            DB::beginTransaction();

            $achat = Achat::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'methode_paiement' => 'stripe',
            ]);

            foreach ($panier as $livreId => $item) {
                $achat->livres()->attach($livreId, [
                    'quantite' => $item['quantite'] ?? 1,
                    'prix_unitaire' => $item['prix'] ?? 0
                ]);
            }

            DB::commit();

            $request->session()->forget('panier');

            return response()->json([
                'status' => $status,
                'achat_id' => $achat->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur confirmation Stripe: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
