@extends('layouts.app')

@section('content')
    <h1 class="mb-4">✏️ Modifier le livre : {{ $livre->titre }}</h1>

    <form action="{{ route('livres.update', $livre) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" value="{{ old('titre', $livre->titre) }}" required>
            @error('titre')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Auteur</label>
            <input type="text" name="auteur" class="form-control" value="{{ old('auteur', $livre->auteur) }}" required>
            @error('auteur')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <input type="text" name="categorie" class="form-control" value="{{ old('categorie', $livre->categorie) }}" required>
            @error('categorie')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Année de publication</label>
            <input type="number" name="annee_publication" class="form-control" value="{{ old('annee_publication', $livre->annee_publication) }}" required>
            @error('annee_publication')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Résumé</label>
            <textarea name="resume" class="form-control" rows="3" required>{{ old('resume', $livre->resume) }}</textarea>
            @error('resume')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Prix (€)</label>
            <input type="number" step="0.01" name="prix" class="form-control" value="{{ old('prix', $livre->prix) }}" required>
            @error('prix')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning">💾 Enregistrer</button>
        <a href="{{ route('livres.index') }}" class="btn btn-secondary">↩ Retour</a>
    </form>
@endsection
