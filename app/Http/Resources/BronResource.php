<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BronResource extends JsonResource
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
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'booking_time' => $this->booking_time,
            'services' => $this->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name_uz' => $service->name_uz,
                    'name_ru' => $service->name_ru,
                    'price' => $service->pivot->price,
                ];
            }),
        ];
    }
    
}
