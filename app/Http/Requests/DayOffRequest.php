<?php
// App\Http\Requests\DayOffRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DayOffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // keyin auth middleware orqali cheklash mumkin
    }

    public function rules(): array
    {
        return [
            'day_off' => 'required|date',
        ];
    }
}