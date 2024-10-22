<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'rooms' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'mq' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'services' => 'nullable'
        ];

        // Rendi obbligatorio il campo immagini solo per la creazione
        if ($this->isMethod('post')) {
            $rules['images'] = 'required|array|min:1';
            $rules['images.*'] = 'image|max:10240';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'title.required' => 'Il campo Titolo è obbligatorio.',
            'title.string' => 'Il Titolo deve essere una stringa valida.',
            'title.max' => 'Il Titolo non può superare i :max caratteri.',
            'rooms.required' => 'Il campo N. Camere è obbligatorio.',
            'rooms.numeric' => 'Il N. Camere deve essere un numero.',
            'rooms.min' => 'Il N. Camere deve essere almeno :min.',
            'beds.required' => 'Il campo N. Letti è obbligatorio.',
            'beds.numeric' => 'Il  N. Letti deve essere un numero.',
            'beds.min' => 'Il  N. Letti deve essere almeno :min.',
            'bathrooms.required' => 'Il campo N. Bagni è obbligatorio.',
            'bathrooms.numeric' => 'Il N. Bagni deve essere un numero.',
            'bathrooms.min' => 'Il N. Bagni deve essere almeno :min.',
            'mq.required' => 'Il campo Metri Quadri è obbligatorio.',
            'mq.numeric' => 'Il campo Metri Quadri deve essere un numero.',
            'mq.min' => 'I Metri Quadri devono essere almeno :min.',
            'address.required' => 'Il campo Indirizzo è obbligatorio.',
            'address.string' => 'L\'Indirizzo deve essere una stringa valida.',
            'address.max' => 'L\'Indirizzo non può superare i :max caratteri.',
            'images.required' => 'Il campo Immagine è obbligatorio.',
            'images.array' => 'Il campo Immagine deve essere una lista',
            'images.max' => 'Puoi inserire massimo :max immagini',
            'images.*.file' => 'Ogni immagine deve essere un file.',
            'images.*.mimes' => 'Ogni file deve essere di tipo: jpg, jpeg, png, webp.',
            'images.*.max' => 'Ogni immagine non può superare i :max kilobyte.',
            'is_visible.required' => 'Il campo Visibile è obbligatorio.',
            'is_visible.boolean' => 'Il campo Visibile deve essere si o no',
            'services.exists' => 'Il servizio selezionato non è valido. Seleziona un servizio esistente.',
        ];
    }
}
