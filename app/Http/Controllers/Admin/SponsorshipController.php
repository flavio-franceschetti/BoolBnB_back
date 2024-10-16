<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Braintree\Gateway;
use Illuminate\Support\Facades\Log;

class SponsorshipController extends Controller
{
    protected $gateway;

    public function __construct()
    {
        // Configura Braintree Gateway
        $this->gateway = new Gateway([
            'environment' => env('BRAINTREE_ENV'), // Es. 'sandbox' o 'production'
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
        ]);
    }

    /**
     * Show the purchase page for a specific sponsorship.
     */
    public function purchase($id)
    {
        // Recupera la sponsorizzazione con l'ID
        $sponsorship = Sponsorship::findOrFail($id);

        // Genera un token di pagamento con Braintree
        $clientToken = $this->gateway->clientToken()->generate();

        // Mostra la pagina di acquisto e passa i dati della sponsorizzazione e il token
        return view('admin.sponsorships.purchase', [
            'sponsorship' => $sponsorship,
            'clientToken' => $clientToken
        ]);
    }

    /**
     * Handle the payment after the form submission.
     */
    public function processPayment(Request $request)
    {
        // Validazione della richiesta
        $request->validate([
            'payment_method_nonce' => 'required',
            'sponsorship_id' => 'required|exists:sponsorships,id',
        ]);

        // Ottieni i dati inviati dal form
        $nonceFromTheClient = $request->payment_method_nonce;
        $sponsorshipId = $request->sponsorship_id;

        // Recupera la sponsorizzazione
        $sponsorship = Sponsorship::findOrFail($sponsorshipId);

        // Effettua la transazione tramite Braintree
        $result = $this->gateway->transaction()->sale([
            'amount' => $sponsorship->price,
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        // Controlla se la transazione ha avuto successo
        if ($result->success) {
            // Aggiorna lo stato della sponsorizzazione dell'utente, salva i dettagli del pagamento, ecc.
            // Qui puoi aggiornare la sponsorizzazione o eseguire altre logiche
            return redirect()->route('admin.apartments.index')->with('success', 'Sponsorizzazione acquistata con successo!');
        } else {
            // Log degli errori per la diagnosi
            Log::error('Braintree Transaction Failed', [
                'error_code' => $result->code,
                'message' => $result->message,
                'sponsorship_id' => $sponsorshipId,
            ]);

            // In caso di fallimento, reindirizza l'utente con un messaggio di errore
            return redirect()->back()->withErrors('Errore durante il pagamento: ' . $result->message);
        }
    }
}
