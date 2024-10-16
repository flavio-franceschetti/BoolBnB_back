@extends('layouts.app')

@section('content')
    <h1>Inserisci i dati per il nuovo appartamento</h1>

    <form action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titolo annuncio</label>
            <input type="text" class="form-control" id="title" name="title">
          </div>
        <div class="mb-3">
            <label for="rooms" class="form-label">N. Camere</label>
            <input type="number" class="form-control" id="rooms" name="room">
          </div>
        <div class="mb-3">
            <label for="beds" class="form-label">N. Letti</label>
            <input type="number" class="form-control" id="beds" name="beds">
          </div>
        <div class="mb-3">
            <label for="bathroom" class="form-label">N. Bagni</label>
            <input type="number" class="form-control" id="bathroom" name="bathroom">
          </div>
        <div class="mb-3">
            <label for="mq" class="form-label">mq.</label>
            <input type="number" class="form-control" id="mq" name="mq">
          </div>
        <div class="mb-3">
            <label for="address" class="form-label">Indirizzo</label>
            <input type="text" class="form-control" id="address" name="address">
          </div>
        <div class="mb-3">
            <label for="img" class="form-label">Immagine</label>
            <input class="form-control" type="file" id="img" name="img">
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
        </div>
    </form>
@endsection