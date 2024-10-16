@extends('layouts.app')

@section('content')
    <h1>Inserisci i dati per il nuovo appartamento</h1>

    <form action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titolo annuncio</label>
            <input type="text" class="form-control" id="title" name="title">
          </div>

          <div class="d-flex gap-3 mb-3">
            <div>
                <label for="rooms" class="form-label">N. Camere</label>
                <input type="number" class="form-control" id="rooms" name="room">
              </div>
            <div>
                <label for="beds" class="form-label">N. Letti</label>
                <input type="number" class="form-control" id="beds" name="beds">
              </div>
            <div>
                <label for="bathroom" class="form-label">N. Bagni</label>
                <input type="number" class="form-control" id="bathroom" name="bathroom">
              </div>
              <div>
                  <label for="mq" class="form-label">mq.</label>
                  <input type="number" class="form-control" id="mq" name="mq">
                </div>
          </div>
          <div class="d-flex gap-3 mb-3">
            <div>
                <label for="address" class="form-label">Indirizzo</label>
                <input type="text" class="form-control" id="address" name="address">
              </div>
              <div>
                <label for="civic_number" class="form-label">N. Civico</label>
                <input type="number" class="form-control" id="civic_number" name="civic_number">
              </div>
            <div>
                <label for="city" class="form-label">Città</label>
                <input type="text" class="form-control" id="city" name="city">
              </div>
            <div>
                <label for="postal_code" class="form-label">CAP</label>
                <input type="number" class="form-control" id="postal_code" name="postal_code">
              </div>
          </div>

          <div class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
            @foreach ($services as $service)
            <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check" id="service-{{ $service->id }}" autocomplete="off">
            <label class="btn btn-outline-primary" for="service-{{ $service->id }}">{{ $service->name }}</label>
            @endforeach
          </div>

        <div class="mb-3">
            <label for="img" class="form-label">Immagine</label>
            <input class="form-control" type="file" id="img" name="img[]" multiple>
        </div>
        <div class="is-visible-radios mb-3">
            <div>Visibile</div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                  Si
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="0" id="flexRadioDefault2" >
                <label class="form-check-label" for="flexRadioDefault2">
                  No
                </label>
              </div>
        </div>
        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Invia">
            <input type="reset" class="btn btn-danger" value="Annulla">
        </div>
    </form>
@endsection