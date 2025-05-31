<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminBronRequest extends FormRequest
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
      'user_id' => 'required|integer|exists:users,id',
        'user_name' => 'required|string',
        'user_phone' => 'required|string',
        'booking_time' => 'required|date',
        'services' => 'required|array|min:1',
        'services.*.id' => 'required|integer|exists:services,id',
        ];
    }
    
}
