@extends('layouts.app')

@section('content')
    <h1 class="mb-4">💳 Paiement</h1>

    @if(empty($panier))
        <div class="alert alert-info">Votre panier est vide.</div>
    @else
        @php $total = 0; @endphp
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($panier as $id => $item)
                    @php $total += $item['prix'] * $item['quantite']; @endphp
                    <tr>
                        <td>{{ $item['titre'] }}</td>
                        <td>{{ $item['prix'] }} €</td>
                        <td>{{ $item['quantite'] }}</td>
                        <td>{{ $item['prix'] * $item['quantite'] }} €</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total :</strong></td>
                    <td><strong>{{ $total }} €</strong></td>
                </tr>
            </tbody>
        </table>

        <h2 class="mb-3">Choisissez votre méthode de paiement :</h2>

        <div class="d-flex gap-3 mb-4">
            <button class="btn btn-outline-primary active" id="paypal-tab" onclick="switchPaymentMethod('paypal')">
                PayPal
            </button>
            <button class="btn btn-outline-primary" id="stripe-tab" onclick="switchPaymentMethod('stripe')">
                Carte de crédit (Stripe)
            </button>
        </div>

        <div id="paypal-section">
            <div id="paypal-button-container"></div>
        </div>

        <div id="stripe-section" style="display: none;">
            <form id="payment-form">
                <div id="card-element" class="form-control mb-3 p-2" style="height: 40px;"></div>
                <button id="submit-stripe" class="btn btn-primary">
                    <div class="spinner-border spinner-border-sm" id="spinner" style="display: none;"></div>
                    <span id="button-text">Payer {{ $total }} €</span>
                </button>
            </form>
        </div>

        <div id="payment-error" class="alert alert-danger mt-3" style="display: none;"></div>

        <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=EUR"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            // Stripe
            const stripe = Stripe('{{ env('STRIPE_KEY') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                    },
                },
            });
            cardElement.mount('#card-element');

            // Gestionnaire de soumission du formulaire Stripe
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitButton = document.getElementById('submit-stripe');
                const spinner = document.getElementById('spinner');
                const buttonText = document.getElementById('button-text');

                submitButton.disabled = true;
                spinner.style.display = 'inline-block';
                buttonText.textContent = 'Traitement...';

                try {
                    // Créer l'intention de paiement
                    const response = await fetch('{{ route('stripe.create-payment-intent') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ panier: @json($panier) })
                    });

                    const { client_secret, error } = await response.json();

                    if (error) {
                        throw new Error(error);
                    }

                    // Confirmer le paiement
                    const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
                        payment_method: {
                            card: cardElement,
                        }
                    });

                    if (stripeError) {
                        throw new Error(stripeError.message);
                    }

                    // Paiement réussi
                    alert('Paiement effectué avec succès !');
                    window.location.href = '{{ route('payment.success') }}';
                } catch (error) {
                    document.getElementById('payment-error').textContent = error.message;
                    document.getElementById('payment-error').style.display = 'block';
                    console.error('Erreur:', error);
                } finally {
                    submitButton.disabled = false;
                    spinner.style.display = 'none';
                    buttonText.textContent = `Payer {{ $total }} €`;
                }
            });

            // PayPal
            paypal.Buttons({
                createOrder: function (data, actions) {
                    return fetch('{{ route('paypal.checkout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ panier: @json($panier) })
                    })
                        .then(response => response.json())
                        .then(order => {
                            if (order.error) {
                                document.getElementById('payment-error').textContent = order.error;
                                document.getElementById('payment-error').style.display = 'block';
                                throw new Error(order.error);
                            }
                            return order.id;
                        });
                },
                onApprove: function (data, actions) {
                    return fetch('{{ route('paypal.capture') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ orderID: data.orderID })
                    })
                        .then(response => response.json())
                        .then(details => {
                            if (details.error) {
                                document.getElementById('payment-error').textContent = details.error;
                                document.getElementById('payment-error').style.display = 'block';
                                throw new Error(details.error);
                            }

                            alert('Paiement effectué avec succès !');
                            window.location.href = '{{ route('payment.success') }}';
                        });
                },
                onError: function (err) {
                    document.getElementById('payment-error').textContent = 'Une erreur est survenue lors du paiement. Veuillez réessayer.';
                    document.getElementById('payment-error').style.display = 'block';
                    console.error('Erreur PayPal:', err);
                }
            }).render('#paypal-button-container');

            // Fonction pour basculer entre les méthodes de paiement
            function switchPaymentMethod(method) {
                if (method === 'paypal') {
                    document.getElementById('paypal-tab').classList.add('active');
                    document.getElementById('stripe-tab').classList.remove('active');
                    document.getElementById('paypal-section').style.display = 'block';
                    document.getElementById('stripe-section').style.display = 'none';
                } else {
                    document.getElementById('stripe-tab').classList.add('active');
                    document.getElementById('paypal-tab').classList.remove('active');
                    document.getElementById('stripe-section').style.display = 'block';
                    document.getElementById('paypal-section').style.display = 'none';
                }
                document.getElementById('payment-error').style.display = 'none';
            }
        </script>
        <style>
            .payment-tab {
                padding: 10px 20px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                background-color: #f8f9fa;
                cursor: pointer;
                margin-right: 10px;
            }

            .payment-tab.active {
                background-color: #007bff;
                color: white;
                border-color: #007bff;
            }

            #card-element {
                height: 40px;
                padding: 10px;
            }
        </style>
    @endif
@endsection