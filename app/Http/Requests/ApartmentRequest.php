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
            'title'        => 'required|string|max:255',
            'room'         => 'required|numeric|min:1',
            'beds'         => 'required|numeric|min:1',
            'bathroom'     => 'required|numeric|min:1',
            'mq'           => 'required|numeric|min:30',
            'address'      => 'required|string|max:255',
            'postal_code' => 'required',
            'city'         => 'required|string|max:255',
            'civic_number' => 'required|numeric|min:1',
            'img' => 'required|array',
            'img.*' => 'file|mimes:jpg,jpeg,png,webp|max:30720',
            'is_visible'   => 'required|boolean',
            'services'  => 'nullable|exists:services,id'
        ];
    }
}
