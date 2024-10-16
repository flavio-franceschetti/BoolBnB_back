@extends('layouts.app')

@section('content')
    <h1>Questo è l'index degli appartamenti</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Titolo</th>
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
                    <td>{{ $apartment->id }}</td>
                    <td>{{ $apartment->title }}</td>
                    <td>{{ $apartment->rooms }}</td>
                    <td>{{ $apartment->beds }}</td>
                    <td>{{ $apartment->bathrooms }}</td>
                    <td>{{ $apartment->mq }}</td>
                    <td>{{ $apartment->address }}</td>
                    <td>
                        <a href="{{ route('admin.apartments.show', $apartment) }}" class="btn btn-warning">Dettagli</a>
                        <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-success">Modifica</a>
                        <form class="d-inline" action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Elimina</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
