@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">📚 Nouveautés (10 derniers jours)</h1>

    @if($livres->isEmpty())
        <div class="alert alert-info text-center">
            Aucun livre ajouté récemment.
        </div>
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
                            <p class="card-text mb-1"><strong>Prix:</strong> {{ $livre->prix }}€</p>
                            <p class="card-text mt-auto"><small class="text-muted">Ajouté le {{ $livre->created_at->format('d/m/Y') }}</small></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('livres.show', $livre) }}" class="btn btn-sm btn-info">👁 Voir</a>
                            <a href="{{ route('livres.edit', $livre) }}" class="btn btn-sm btn-warning">✏️ Éditer</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('livres.index') }}" class="btn btn-secondary">← Retour à la liste</a>
    </div>
</div>
@endsection
