@extends('layouts.app')

@section('content')
<div class="p-4">
    @if (count($apartments) > 0)
    <h1>Questi sono i tuoi appartamenti</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Titolo</th>
                <th scope="col">Indirizzo</th>
                <th scope="col">Visibilità</th>
                <th scope="col">Fine sponsorizzazione</th>
                <th scope="col">Visualizzazioni</th>
                <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apartments as $apartment)
            <tr>
                <td class="text-truncate" style="max-width: 150px;">{{ $apartment->title }}</td>
                <td>{{ $apartment->address }}</td>
                <td>{{ $apartment->is_visible ? 'Si' : 'No' }}</td>
                <td>
                    @if ($apartment->lastEndDate())
                    {{-- Format the date to "F j, Y" (e.g., November 3, 2024) --}}
                    {{ \Carbon\Carbon::parse($apartment->lastEndDate())->format('d-m-Y') }}
                    @else
                    Nessuna sponsorizzazione attiva
                    @endif
                </td>
                <td>{{ $apartment->views->count()}}</td>
                <td>
                    <a href="{{ route('admin.apartments.show', $apartment) }}" class="btn btn-warning">Dettagli</a>
                    <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-success">Modifica</a>
                    <form class="d-inline" action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST"
                        onsubmit="return confirm('vuoi eliminare questo appartamento?')">
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
    <a class="btn btn-success" href="{{ route('admin.apartments.create') }}">Aggiungi il tuo primo appartamento</a>
    @endif
</div>
@endsection