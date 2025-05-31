<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarberRequest extends FormRequest
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
            'username' => 'required|string|max:255|unique:users,username', // username tekshiruvi
            'name' => 'required|string|max:255', // ism tekshiruvi
            'lastname' => 'required|string|max:255', // familiya tekshiruvi
            'phone' => 'required|string|max:20', // telefon raqami
            'password' => 'required|string|min:6', // parol
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // rasmni tekshirish
        ];
    }
}
