<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userApartments = Auth::user()->apartments->pluck('id'); // Prendo gli ID degli appartamenti dell'utente
        $messages = Message::whereIn('apartment_id', $userApartments)->orderBy('id')->get(); // Filtro i messaggi per appartamenti dell'utente

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validazione dei dati inviati dall'utente
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email',
            'content' => 'required|min:10',
            'apartment_id' => 'required'
        ]);

        // Creazione di un nuovo contatto nel database
        $message = Message::create($validatedData);

        // Risposta JSON di successo con i dati inseriti
        return response()->json([
            'message' => 'Contatto creato con successo!',
            'data' => $message
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {

        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index');
    }
}
