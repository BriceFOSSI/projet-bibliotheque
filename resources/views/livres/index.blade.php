@extends('layouts.app')

@section('content')
    <h1 class="mb-4">📚 Liste des livres</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->check() && auth()->user()->isAdmin())
        {{-- 🛠️ Vue Admin en tableau --}}
        <a href="{{ route('livres.create') }}" class="btn btn-primary mb-3">➕ Ajouter un livre</a>

        @if($livres->isEmpty())
            <div class="alert alert-info">Aucun livre disponible.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Année</th>
                        <th>Prix (€)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($livres as $livre)
                        <tr>
                            <td>{{ $livre->titre }}</td>
                            <td>{{ $livre->auteur }}</td>
                            <td>{{ $livre->categorie }}</td>
                            <td>{{ $livre->annee_publication }}</td>
                            <td>{{ $livre->prix }}</td>
                            <td>
                                <a href="{{ route('livres.show', $livre) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                                <a href="{{ route('livres.edit', $livre) }}" class="btn btn-warning btn-sm">✏️ Éditer</a>
                                <form action="{{ route('livres.destroy', $livre) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce livre ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        {{-- 👤 Vue Client en cartes --}}
        @if($livres->isEmpty())
            <div class="alert alert-info">Aucun livre disponible.</div>
        @else
            <div class="row">
                @foreach($livres as $livre)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $livre->titre }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $livre->auteur }}</h6>
                                <p class="card-text"><strong>Catégorie :</strong> {{ $livre->categorie }}</p>
                                <p class="card-text"><strong>Année :</strong> {{ $livre->annee_publication }}</p>
                                <p class="card-text text-success fw-bold">{{ $livre->prix }} €</p>

                                <div class="mt-auto">
                                    <a href="{{ route('livres.show', $livre) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                                    <form action="{{ route('panier.ajouter', $livre->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">🛒 Ajouter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
@endsection
