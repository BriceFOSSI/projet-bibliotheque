@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">📩 Messages reçus</h1>

    @if($messages->isEmpty())
        <div class="alert alert-info text-center">
            Aucun message reçu pour l’instant.
        </div>
    @else
        <div class="row">
            @foreach($messages as $msg)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $msg->sujet }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $msg->nom }} — {{ $msg->email }}</h6>
                            <p class="card-text">{{ $msg->message }}</p>
                        </div>
                        <div class="card-footer text-end">
                            <small class="text-muted">Reçu le {{ $msg->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
