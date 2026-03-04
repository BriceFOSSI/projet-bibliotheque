@extends('layouts.app')

@section('content')
<h1>🧾 Historique des Achats</h1>

@if($achats->isEmpty())
    <p>Vous n’avez encore effectué aucun achat.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Livres</th>
                <th>Total payé</th>
                <th>Méthode de paiement</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($achats as $achat)
                <tr>
                    <td>
                        @foreach($achat->livres as $livre)
                            {{ $livre->titre }} (x{{ $livre->pivot->quantite }})<br>
                        @endforeach
                    </td>
                    <td>{{ $achat->total }} €</td>
                    <td>{{ $achat->methode_paiement }}</td>
                    <td>{{ $achat->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
