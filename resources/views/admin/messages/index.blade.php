@extends('layouts.app')

@section('content')
@if (count($messages) > 0)
<h1>Messaggi ricevuti: {{ count($messages) }}</h1>
<div class="container-fluid">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Titolo</th>
            <th scope="col">Da:</th>
            <th scope="col">Email</th>
            <th scope="col">Messaggio</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($messages as $message)
          <tr>
            <th class="card-title" scope="row">{{ $message->apartment->title }}</th>
            <td class="card-subtitle">{{ $message->name }} {{ $message->surname }}</td>
            <td class="card-text">{{ $message->email }}</td>
            <td class="card-text">{{ $message->content }}</td>
            <td>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary">Visualizza</a>
                </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    {{-- <div class="row">
        @foreach ($messages as $message)
        <div class="card m-3 col-6" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{ $message->apartment->title }}</h5>
                <p class="card-title">{{ $message->apartment->address }}</p>
                <h5 class="card-title">Da: {{ $message->name }} {{ $message->surname }}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Email: {{ $message->email }}</h6>
                <div>{{ $message->created_at->format('d-m-Y H:i') }}</div>
                <p class="card-text">{{ $message->content }}</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary">Visualizza</a>
                </div>
            </div>
        </div>
        @endforeach
    </div> --}}
</div>
@else
<h3>Non hai ricevuto nessun messaggio per il momento</h3>
@endif
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