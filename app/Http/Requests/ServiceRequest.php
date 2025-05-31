<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ruxsat beriladi
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_uz' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'price' => 'required|numeric|min:0',
             'minut' => 'required|string|max:4',
        ];
    }
}

