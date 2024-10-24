@extends('layouts.app')

@section('content')
    <h3>Dettaglio del messaggio</h3>
    <div>Titolo annuncio: {{ $message->apartment->title }}</div>
    <div>Mittente: {{ $message->name }} {{ $message->surname }}</div>
    <div>Email: {{ $message->email }}</div>
    <div>Messaggio: {{ $message->content }}</div>
    <div>Ricevuto il: {{ $message->created_at->format('d-m-Y') }}</div>

    <a class="btn btn-primary" href="{{ route('admin.messages.index') }}">Torna ai messaggi</a>
@endsection