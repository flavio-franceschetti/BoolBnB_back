@extends('layouts.app')

@section('content')
    <h1>Messaggi ricevuti</h1>
    <div class="container-fluid">
        <div class="row">
            @foreach ($messages as $message)
                <div class="card m-3 col-6" style="width: 18rem;">
                    <div class="card-body">
                    <h5 class="card-title">Da: {{ $message->name }} {{ $message->surname }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Email: {{ $message->email }}</h6>
                    <div>{{ $message->created_at->format('d-m-Y') }}</div>
                    <p class="card-text">{{ $message->content }}</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary">Visualizza</a>
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Sicuro di voler eliminare questo messaggio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Elimina</button>
                        </form>
                    </div>
                    </div>
                </div>
             @endforeach
        </div>
    </div>
 <style>
    .card-text{
        white-space: nowrap;       /* Impedisce che il testo vada a capo */
        overflow: hidden;          /* Nasconde il testo in eccesso */
        text-overflow: ellipsis;   /* Aggiunge i puntini di sospensione (...) */
        width: 200px;  
    }
 </style>
@endsection