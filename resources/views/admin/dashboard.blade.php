{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1 class="mb-4">📊 Tableau de Bord Admin</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Achats</h5>
                    <p class="h3">{{ $totalAchats }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Revenus Totaux</h5>
                    <p class="h3">{{ number_format($revenusTotal, 2) }} €</p>
                </div>
            </div>
        </div>
    </div>

    <h3>🔥 Top 5 des livres les plus achetés</h3>
    <ul class="list-group mb-4">
        @foreach($topLivres as $livre)
            <li class="list-group-item d-flex justify-content-between">
                {{ $livre->titre }}
                <span class="badge bg-primary">{{ $livre->total_vendus }}</span>
            </li>
        @endforeach
    </ul>

    <h3>🧾 Dernières Transactions</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Total</th>
                <th>Méthode de Paiement</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dernieresTransactions as $achat)
                <tr>
                    <td>{{ $achat->user->name }}</td>
                    <td>{{ $achat->total }} €</td>
                    <td>{{ $achat->methode_paiement }}</td>
                    <td>{{ $achat->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
