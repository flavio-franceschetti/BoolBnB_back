@extends('layouts.app')

@section('content')
<div class="p-4">
    <h3>Dettaglio del messaggio</h3>
    <div><strong>Titolo annuncio:</strong> {{ $message->apartment->title }}</div>
    <div><strong>Mittente:</strong> {{ $message->name }} {{ $message->surname }}</div>
    <div><strong>Email:</strong> {{ $message->email }}</div>
    <div><strong>Ricevuto il:</strong> {{ $message->created_at->format('d-m-Y') }}</div>
    <div class="mb-3"><strong>Messaggio:</strong> {{ $message->content }}</div>
    <a class="btn btn-primary" href="{{ route('admin.messages.index') }}">Torna ai messaggi</a>
</div>
@endsection