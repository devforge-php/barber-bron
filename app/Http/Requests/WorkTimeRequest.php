<?php

// App\Http\Requests\WorkTimeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // keyin authni ro'yhatdan o'tkazishda o'zgartirishingiz mumkin
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }
}