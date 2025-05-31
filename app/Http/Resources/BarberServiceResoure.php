<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarberServiceResoure extends JsonResource
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
            'barber_id' => $this->user_id,

            'barber_name' => optional($this->barber)->name,
            'barber_lastname' => optional($this->barber)->lastname,
            'barber_username' => optional($this->barber)->username,

            'name_uz' => $this->name_uz,
            'name_ru' => $this->name_ru,
            'description_uz' => $this->description_uz,
            'description_ru' => $this->description_ru,
            'price' => $this->price,
            'minut' => $this->minut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}