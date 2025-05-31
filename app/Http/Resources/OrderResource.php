<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'barber_name' => optional($this->user)->username, // xavfsiz kirish
            'user_name' => $this->user_name ?? null,
            'user_phone' => $this->user_phone ?? null,
            'booking_time' => $this->booking_time,
            'services' => $this->services, // agar service bo'lmasa ham xatolik chiqmaydi
        ];
    }
}