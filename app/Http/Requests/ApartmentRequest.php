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
    public function rules(): array
    {
        return [

            'title' => 'required|string|max:255',
            'rooms'  => 'required|numeric|min:1',
            'beds'  => 'required|numeric|min:1',
            'bathrooms' => 'required|numeric|min:1',
            'mq' => 'required|numeric|min:30',
            'address'  => 'required|string|max:255',
            'img' => 'required|array',
            'img.*' => 'file|mimes:jpg,jpeg,png,webp|max:10240',
            'is_visible'   => 'required|boolean',
            'services'  => 'nullable|exists:services,id'
        ];
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
            'img.required' => 'Il campo Immagine è obbligatorio.',
            'img.array' => 'Il campo Immagine deve essere una lista',
            'img.*.file' => 'Ogni immagine deve essere un file.',
            'img.*.mimes' => 'Ogni file deve essere di tipo: jpg, jpeg, png, webp.',
            'img.*.max' => 'Ogni immagine non può superare i :max kilobyte.',
            'is_visible.required' => 'Il campo Visibile è obbligatorio.',
            'is_visible.boolean' => 'Il campo Visibile deve essere si o no',
            'services.exists' => 'Il servizio selezionato non è valido. Seleziona un servizio esistente.',
        ];
    }
}
