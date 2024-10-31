@extends('layouts.app')

@section('content')
    <div class="p-4">
        @if (count($messages) > 0)
            <h1>{{ count($messages) }} messaggi ricevuti</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Mittente</th>
                        <th scope="col">Appartamento</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ricevuto il</th>
                        <th scope="col">Info</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->surname }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->created_at->format('d-m-Y | H:i') }}</td>
                            <td><a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary">Visualizza</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Non hai ricevuto nessun messaggio per il momento</h3>
        @endif
    </div>

    <style>
        .card-text {
            white-space: nowrap;
            /* Impedisce che il testo vada a capo */
            overflow: hidden;
            /* Nasconde il testo in eccesso */
            text-overflow: ellipsis;
            /* Aggiunge i puntini di sospensione (...) */
            width: 200px;
        }
    </style>
@endsection
