<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Sponsorship;
use Braintree\Gateway;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $braintree;

    public function __construct()
    {
        $this->braintree = new Gateway([
            'environment' => config('braintree.environment'),
            'merchantId' => config('braintree.merchantId'),
            'publicKey' => config('braintree.publicKey'),
            'privateKey' => config('braintree.privateKey'),
        ]);
    }

    public function showPaymentForm($apartmentId, $sponsorshipId)
    {
        $apartment = Apartment::findOrFail($apartmentId);
        $sponsorship = Sponsorship::findOrFail($sponsorshipId);

        // Genera un token client
        $clientToken = $this->braintree->clientToken()->generate();

        // Restituisci la vista con i dati necessari
        return view('admin.sponsorships.payment', compact('clientToken', 'apartment', 'sponsorship'));
    }

    public function processPayment(Request $request)
    {
        try {
            // Validazione dei dati in ingresso
            $request->validate([
                'payment_method_nonce' => 'required|string',
                'amount' => 'required|numeric',
                'apartment_id' => 'required|integer|exists:apartments,id',
                'sponsorship_id' => 'required|integer|exists:sponsorships,id',
            ]);

            // Estrai i dati dalla richiesta
            $amount = (float)$request->input('amount');
            $paymentMethodNonce = $request->input('payment_method_nonce');
            $apartmentId = $request->input('apartment_id');
            $sponsorshipId = $request->input('sponsorship_id');

            // Elabora il pagamento
            $result = $this->braintree->transaction()->sale([
                'amount' => number_format($amount, 2, '.', ''),
                'paymentMethodNonce' => $paymentMethodNonce,
                'options' => [
                    'submitForSettlement' => true,
                ],
            ]);

            // Log del risultato
            Log::info('Risultato pagamento Braintree: ', (array)$result);

            if ($result->success) {
                // Recupera la sponsorizzazione selezionata
                $sponsorship = Sponsorship::findOrFail($sponsorshipId);
                $durationSeconds = $sponsorship->duration * 3600; // Converti ore in secondi

                // Recupera l'appartamento
                $apartment = Apartment::findOrFail($apartmentId);

                // Verifica se c'è una sponsorizzazione attiva
                $currentSponsorship = $apartment->sponsorships()
                    ->where('sponsorship_id', $sponsorshipId)
                    ->latest('pivot_end_date')
                    ->first();

                // Calcola la nuova data di fine
                if ($currentSponsorship && $currentSponsorship->pivot->end_date > now()) {
                    // Estendi la durata della sponsorizzazione esistente
                    $newEndDate = Carbon::parse($currentSponsorship->pivot->end_date)->addSeconds($durationSeconds);
                    // Cumulare le ore di sponsorizzazione
                    $totalSponsorshipHours = $currentSponsorship->pivot->sponsorship_hours + $sponsorship->duration;
                } else {
                    // Nessuna sponsorizzazione attiva, imposta la nuova data di fine
                    $newEndDate = Carbon::now()->addSeconds($durationSeconds);
                    $totalSponsorshipHours = $sponsorship->duration;
                }

                // Crea o aggiorna l'associazione nella tabella pivot
                $apartment->sponsorships()->syncWithoutDetaching([
                    $sponsorshipId => [
                        'end_date' => $newEndDate,
                        'sponsorship_hours' => $totalSponsorshipHours,
                    ],
                ]);

                // Aggiorna l'appartamento con le informazioni della sponsorizzazione
                $apartment->update([
                    'last_sponsorship' => $sponsorship->name,
                    'sponsorship_price' => $sponsorship->price,
                    'sponsorship_hours' => $apartment->sponsorship_hours + $sponsorship->duration,
                ]);

                // Reindirizza all'appartamento modificato con un messaggio di successo
                return response()->json(['success' => true, 'redirect_url' => route('admin.apartments.show', $apartmentId)]);
            } else {
                // Log dell'errore di pagamento
                Log::error('Errore di pagamento Braintree: ', (array)$result);
                return response()->json(['success' => false, 'error' => $result->message], 500);
            }
        } catch (\Throwable $e) {
            Log::error('Errore nel processo di pagamento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Si è verificato un errore durante l\'elaborazione del pagamento. Riprova più tardi.',
            ], 500);
        }
    }
}
