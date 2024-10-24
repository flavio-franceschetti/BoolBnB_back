<?php

namespace App\Http\Requests;

use App\Models\ApartmentImage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

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
     * Prepara i dati per la convalida e gestisce la rimozione delle immagini
     */
    // protected function prepareForValidation()
    // {
    //     // Simulate deletion of the images for validation purposes
    //     if ($this->has('delete_images')) {
    //         $remainingImages = array_diff($this->input('existing_images', []), $this->input('delete_images', []));

    //         // Update the count of remaining images for validation purposes
    //         $this->merge([
    //             'existing_images' => $remainingImages,
    //         ]);
    //     }
    // }
    const MAX_IMAGES = 3;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Count the current images associated with the apartment
        $existingImagesCount = count($this->input('existing_images', []));
        $deletedImagesCount = count($this->input('delete_images', []));
        $maxNewImages = self::MAX_IMAGES - ($existingImagesCount - $deletedImagesCount); // Adjusting for deletions

        $rules = [
            'title' => 'required|string|max:255',
            'rooms' => 'required|numeric|min:1',
            'beds' => 'required|numeric|min:1',
            'bathrooms' => 'required|numeric|min:1',
            'mq' => 'required|numeric|min:30|max:2000',
            'address' => 'required|string|max:255',
            'delete_images' => 'nullable|array',
            'is_visible' => 'required|boolean',
            'services' => 'nullable|exists:services,id',
        ];

        // Use required_without_all if all existing images are being deleted
        if ($deletedImagesCount === $existingImagesCount || $existingImagesCount === 0) {
            $rules['images'] = 'required|array|max:' . $maxNewImages;
            $rules['images.*'] = 'file|mimes:jpg,jpeg,png,webp|max:8192';
        } else {
            $rules['images'] = 'array|max:' . $maxNewImages;
            $rules['images.*'] = 'file|mimes:jpg,jpeg,png,webp|max:8192';
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
            'mq.max' => 'I Metri Quadri possono essere massimo :max.',
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
