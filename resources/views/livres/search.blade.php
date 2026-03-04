@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">🔍 Recherche de livres</h1>

        <!-- Formulaire de recherche -->
        <div class="card p-4 mb-5 shadow-sm">
            <form action="{{ route('livres.search') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="titre" class="form-control" placeholder="Titre"
                            value="{{ request('titre') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="auteur" class="form-control" placeholder="Auteur"
                            value="{{ request('auteur') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="categorie" class="form-control" placeholder="Catégorie"
                            value="{{ request('categorie') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="annee_publication" class="form-control" placeholder="Année"
                            value="{{ request('annee_publication') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="prix_min" class="form-control" placeholder="Prix min (€)"
                            value="{{ request('prix_min') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="prix_max" class="form-control" placeholder="Prix max (€)"
                            value="{{ request('prix_max') }}">
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Résultats -->
        @isset($livres)
            <h4 class="mb-3">Résultats ({{ $livres->count() }})</h4>
            @if($livres->isEmpty())
                <div class="alert alert-warning">Aucun livre trouvé.</div>
            @else
                <div class="row">
                    @foreach($livres as $livre)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $livre->titre }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $livre->auteur }}</h6>
                                    <p class="card-text mb-1"><strong>Catégorie:</strong> {{ $livre->categorie }}</p>
                                    <p class="card-text mb-1"><strong>Année:</strong> {{ $livre->annee_publication }}</p>
                                    <p class="card-text mb-1 text-success fw-bold"><strong>Prix:</strong> {{ $livre->prix }}€</p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ route('livres.show', $livre) }}" class="btn btn-sm btn-info">👁 Voir</a>

                                    @auth

                                        @if(!auth()->user()->isAdmin())
                                            <form action="{{ route('panier.ajouter', $livre->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">🛒 Ajouter</button>
                                            </form>
                                        @endif

                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('livres.edit', $livre) }}" class="btn btn-sm btn-warning">✏️ Éditer</a>
                                        @endif
                                    @endauth


                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endisset

    </div>
@endsection