@extends('layouts.app')

@section('content')



<div class="mb-3 d-flex gap-3">
    @foreach ($sponsorships as $sponsorship)
    <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">{{$sponsorship->name}}</h5>
        <h6 class="card-subtitle mb-2 text-body-secondary">€ {{$sponsorship->price}}</h6>
        <p class="card-text">Durata: {{ $sponsorship->duration }} ore</p>
        {{-- nell href andra inserito la route insieme all'id della sponsorship che porta alla pagina di pagamento --}}
        <a href="#" class="card-link">Acquista</a>
      </div>
    </div>
    @endforeach
  </div>
@endsection