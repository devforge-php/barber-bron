<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarberRequestService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // O'zgartiring kerak bo'lsa
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->route()->getActionMethod()) {
            'store' => [
                'name_uz' => 'required|string|max:255',
                'name_ru' => 'required|string|max:255',
                'description_uz' => 'nullable|string',
                'description_ru' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'minut' => 'required|string|max:4',
                
            ],
            'update' => [
               'name_uz' => 'required|string|max:255',
                'name_ru' => 'required|string|max:255',
                'description_uz' => 'nullable|string',
                'description_ru' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                 'minut' => 'required|string|max:4',
            ],
            default => [],
        };
    }
}