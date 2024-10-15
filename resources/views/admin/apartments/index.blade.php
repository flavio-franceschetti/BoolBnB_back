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
          <td>{{$apartment->id}}</td>
          <td>{{$apartment->title}}</td>
          <td>{{$apartment->room}}</td>
          <td>{{$apartment->beds}}</td>
          <td>{{$apartment->bathroom}}</td>
          <td>{{$apartment->mq}}</td>
          <td>{{$apartment->address}}</td>
          <td>
            azioni
          </td>
      
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection
