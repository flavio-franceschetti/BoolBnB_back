<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.braintreegateway.com/web/dropin/1.43.0/js/dropin.min.js"></script>
    <title>Pagamento Sponsorizzazione</title>
</head>

<body>
    <form id="payment-form" action="{{ route('admin.payment.checkout') }}" method="post">
        @csrf
        <div id="dropin-container"></div>
        <button type="submit">Paga</button>
        <input type="hidden" name="payment_method_nonce" id="nonce" />
        <input type="hidden" name="apartment_id" value="{{ $apartment->id }}" />
        <input type="hidden" name="sponsorship_id" value="{{ $sponsorship->id }}" />
        <input type="hidden" name="amount" value="{{ $sponsorship->price }}" />
    </form>

    <div id="message"></div>

    <script>
        var form = document.getElementById('payment-form');
        var messageContainer = document.getElementById('message');

        braintree.dropin.create({
            authorization: '{{ $clientToken }}',
            container: '#dropin-container',
            locale: 'it_IT' // Imposta la lingua del form Braintree in italiano
        }, function(error, dropinInstance) {
            if (error) {
                console.error('Errore durante la creazione del dropin: ', error);
                messageContainer.innerHTML =
                    '<p style="color: red;">Errore durante il caricamento del metodo di pagamento. Riprova.</p>';
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                dropinInstance.requestPaymentMethod(function(error, payload) {
                    if (error) {
                        console.error('Errore durante la richiesta del metodo di pagamento: ',
                            error);
                        messageContainer.innerHTML =
                            '<p style="color: red;">Errore durante la richiesta del metodo di pagamento. Riprova.</p>';
                        return;
                    }

                    // Imposta il nonce nel campo nascosto
                    document.getElementById('nonce').value = payload.nonce;

                    // Invia il form al server
                    fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Messaggio di successo
                                messageContainer.innerHTML =
                                    '<p style="color: green;">Pagamento effettuato con successo! Reindirizzamento in corso...</p>';

                                // Reindirizzamento dopo 2 secondi
                                setTimeout(function() {
                                    window.location.href = data.redirect_url;
                                }, 2000);
                            } else {
                                // Messaggio di errore
                                messageContainer.innerHTML = '<p style="color: red;">Errore: ' +
                                    data.error + '</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Errore di rete:', error);
                            messageContainer.innerHTML =
                                '<p style="color: red;">Si è verificato un errore. Riprova.</p>';
                        });
                });
            });
        });
    </script>
</body>

</html>
