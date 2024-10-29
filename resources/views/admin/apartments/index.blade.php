@extends('layouts.app')

@section('content')

@if (count($apartments) > 0)
<h1>Questi sono i tuoi appartamenti</h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Titolo</th>
            <th scope="col">Visibilità</th>
            <th scope="col">Durata sponsorizzazione</th>
            <th scope="col">Stanze</th>
            <th scope="col">Letti</th>
            <th scope="col">Bagni</th>
            <th scope="col">mq</th>
            <th scope="col">Indirizzo</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($apartments as $apartment)
        <tr>
            <td>{{$apartment->id}}</td>
            <td class="text-truncate" style="max-width: 150px;">{{$apartment->title}}</td>
            <td>{{$apartment->is_visible ? 'Si' : 'No'}}</td>
            <td>{{$apartment->sponsorship_hours ? $apartment->sponsorship_hours . 'h' : 'Nessuna sponsorizzazione
                attiva'}}</td>
            <td>{{$apartment->rooms}}</td>
            <td>{{$apartment->beds}}</td>
            <td>{{$apartment->bathrooms}}</td>
            <td>{{$apartment->mq}}</td>
            <td>{{$apartment->address}}</td>
            <td>
                <a href="{{route('admin.apartments.show', $apartment)}}" class="btn btn-warning">Dettagli</a>
                <a href="{{route('admin.apartments.edit', $apartment)}}" class="btn btn-success">Modifica</a>
                <form class="d-inline" action="{{route('admin.apartments.destroy', $apartment)}}" method="POST"
                    onsubmit="return confirm('vuoi eliminare questo appartamneto?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Elimina</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@else
<h5>Non hai ancora aggiunto il tuo primo appartamento. Fallo subito da qui!!!</h5>
<a class="btn btn-success" href="{{route('admin.apartments.create')}}">Aggiungi il tuo primo appartamento</a>
@endif

@endsection