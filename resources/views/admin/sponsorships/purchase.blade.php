@extends('layouts.app')

@section('content')
    <h1>Acquista Sponsorizzazione: {{ $sponsorship->name }}</h1>
    <p>Prezzo: €{{ $sponsorship->price }}</p>

    <form action="{{ route('admin.sponsorships.process') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="sponsorship_id" value="{{ $sponsorship->id }}">

        <div id="dropin-container"></div>

        <button type="submit" class="btn btn-primary">Paga</button>
    </form>

    <script src="https://js.braintreegateway.com/web/3.90.0/js/client.min.js"></script>
    <script src="https://js.braintreegateway.com/web/3.90.0/js/hosted-fields.min.js"></script>
    <script>
        var form = document.getElementById('payment-form');
        var clientToken = "{{ $clientToken }}"; // Assicurati che sia definito nel controller

        braintree.dropin.create({
            authorization: clientToken,
            container: 'dropin-container'
        }, function(createErr, instance) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                instance.requestPaymentMethod(function(err, payload) {
                    if (err) {
                        console.error(err);
                        return;
                    }

                    // Aggiungi il nonce al modulo
                    var nonceInput = document.createElement('input');
                    nonceInput.setAttribute('type', 'hidden');
                    nonceInput.setAttribute('name', 'payment_method_nonce');
                    nonceInput.setAttribute('value', payload.nonce);
                    form.appendChild(nonceInput);

                    // Invia il modulo
                    form.submit();
                });
            });
        });
    </script>
@endsection
