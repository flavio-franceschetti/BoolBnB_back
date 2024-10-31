<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.braintreegateway.com/web/dropin/1.43.0/js/dropin.min.js"></script>
    <title>Pagamento Sponsorizzazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;

        }

        h1 {
            text-align: center;
            color: #28a745;
        }

        #payment-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;

        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .popup {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
            position: relative;

        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #28a745;

        }

        #popupTitle {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 10px;

        }

        #popupContent {
            font-size: 18px;
            color: #555;

        }

        .key-icon {
            width: 30px;
            vertical-align: middle;
            margin-left: 5px;

        }

        #message {
            margin-top: 20px;
            text-align: center;

        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #28a745;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            display: none;
            margin: auto;

        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <h1>Paga la Sponsorizzazione</h1>
    <form id="payment-form" action="{{ route('admin.payment.checkout') }}" method="post">
        @csrf
        <div id="dropin-container"></div>
        <button type="submit">Paga</button>
        <input type="hidden" name="payment_method_nonce" id="nonce" />
        <input type="hidden" name="apartment_id" value="{{ $apartment->id }}" />
        <input type="hidden" name="sponsorship_id" value="{{ $sponsorship->id }}" />
        <input type="hidden" name="amount" value="{{ $sponsorship->price }}" />
    </form>

    <div id="message">
        <div class="loader" id="loader"></div>
    </div>

    <!-- Overlay e Pop-up -->
    <div id="overlay">
        <div class="popup">
            <button class="close-btn" id="closePopup">&times;</button>
            <h2 id="popupTitle">Pagamento confermato! <img src="https://img.icons8.com/ios-filled/50/28a745/key.png"
                    alt="Key" class="key-icon" /></h2>
            <p id="popupContent">Grazie per aver scelto <strong>BoolBnB</strong>!</p>
        </div>
    </div>

    <script>
        var form = document.getElementById('payment-form');
        var overlay = document.getElementById('overlay');
        var popupTitle = document.getElementById('popupTitle');
        var popupContent = document.getElementById('popupContent');
        var loader = document.getElementById('loader');
        var closePopupButton = document.getElementById('closePopup');

        braintree.dropin.create({
            authorization: '{{ $clientToken }}',
            container: '#dropin-container',
            locale: 'it_IT'
        }, function(error, dropinInstance) {
            if (error) {
                console.error('Errore durante la creazione del dropin: ', error);
                alert('Errore durante il caricamento del metodo di pagamento. Riprova.');
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                // Mostra il loader
                loader.style.display = 'block';
                // Nascondi l'overlay inizialmente
                overlay.style.display = 'none';
                dropinInstance.requestPaymentMethod(function(error, payload) {
                    if (error) {
                        console.error('Errore durante la richiesta del metodo di pagamento: ',
                            error);
                        // Nascondi il loader
                        loader.style.display = 'none';
                        alert('Errore durante la richiesta del metodo di pagamento. Riprova.');
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
                            // Nascondi il loader
                            loader.style.display = 'none';
                            if (data.success) {
                                // Mostra il pop-up di successo
                                overlay.style.display = 'flex'; // Mostra l'overlay
                                // Cambia il titolo del pop-up
                                popupTitle.innerHTML =
                                    'Pagamento confermato! <img src="https://img.icons8.com/ios-filled/50/28a745/key.png" alt="Key" class="key-icon" />';

                                // Reindirizzamento dopo 2 secondi (opzionale)
                                setTimeout(function() {
                                    window.location.href = data.redirect_url;
                                }, 2000);
                            } else {
                                // Messaggio di errore
                                alert('Errore: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Errore di rete:', error);
                            // nasconde il loader
                            loader.style.display = 'none';
                            alert('Si è verificato un errore. Riprova.');
                        });
                });
            });
        });

        // Chiudi il pop-up
        closePopupButton.addEventListener('click', function() {
            // Nasconde l'overlay
            overlay.style.display = 'none';
        });
    </script>
</body>

</html>
