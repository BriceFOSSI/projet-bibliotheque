@extends('layouts.app')

@section('content')
    {{-- Messages flash --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <h1 class="mb-4">📖 {{ $livre->titre }}</h1>

    <div class="card p-4 shadow">
        <h5><strong>Auteur :</strong> {{ $livre->auteur }}</h5>
        <p><strong>Catégorie :</strong> {{ $livre->categorie }}</p>
        <p><strong>Année de publication :</strong> {{ $livre->annee_publication }}</p>
        <p class="text-success fw-bold"><strong>Prix :</strong> {{ $livre->prix }} €</p>
        
        <hr>
        <p><strong>Résumé :</strong></p>
        <p>{{ $livre->resume }}</p>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('livres.index') }}" class="btn btn-secondary">↩ Retour</a>

        {{-- Bouton "Ajouter au panier" --}}
        @if(auth()->check() && !auth()->user()->isAdmin())
        <form action="{{ route('panier.ajouter', $livre->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">🛒 Ajouter au panier</button>
        </form>
        @endif
    </div>
@endsection
