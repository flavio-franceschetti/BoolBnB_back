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
                $redirectUrl = route('admin.apartments.show', $apartmentId);
                return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
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

    public function checkout(Request $request)
    {
        try {
            // Validazione dei dati in ingresso
            $request->validate([
                'sponsorship_id' => 'required|exists:sponsorships,id',
                'payment_method_nonce' => 'required',
                'apartment_id' => 'required|integer|exists:apartments,id',
                'amount' => 'required|numeric',
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
                // Recupera la durata della sponsorizzazione in ore
                $sponsorship = Sponsorship::findOrFail($sponsorshipId);
                $durationHours = $sponsorship->duration;

                Log::info('Durata Sponsorizzazione: ', ['duration' => $durationHours]);

                // Imposta la data di fine
                $endDate = Carbon::now()->addHours($durationHours);

                // Aggiorna il campo sponsorship_hours dell'appartamento
                $apartment = Apartment::findOrFail($apartmentId);
                $apartment->sponsorship_hours += $durationHours;
                $apartment->save();

                Log::info('Sponsorship hours aggiornate: ', ['sponsorship_hours' => $apartment->sponsorship_hours]);

                // Crea l'associazione nella tabella pivot
                $apartment->sponsorships()->attach($sponsorshipId, [
                    'end_date' => $endDate,
                ]);

                Log::info('Sponsorship associata all\'appartamento: ', [
                    'apartment_id' => $apartmentId,
                    'sponsorship_id' => $sponsorshipId,
                    'end_date' => $endDate,
                ]);

                $redirectUrl = route('admin.apartments.show', $apartmentId);
                return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
            } else {
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
