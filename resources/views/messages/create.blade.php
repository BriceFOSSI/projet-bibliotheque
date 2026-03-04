@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">📩 Contacter la Bibliothèque</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Formulaire -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4">
                <form action="{{ route('messages.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="nom" placeholder="Votre nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" placeholder="Votre email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="sujet" placeholder="Sujet" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="message" placeholder="Votre message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">📤 Envoyer</button>
                </form>
            </div>
        </div>

        <!-- Informations bibliothèque -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Informations de la bibliothèque</h4>
                <p><strong>Adresse :</strong> 123 Rue des Livres, Ville</p>
                <p><strong>Téléphone :</strong> +237 699 99 99 99</p>
                <p><strong>Email :</strong> contact@bibliotheque.com</p>
            </div>
        </div>
    </div>
</div>
@endsection
