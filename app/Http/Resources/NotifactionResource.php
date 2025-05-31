<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray(Request $request): array
{
    $data = is_string($this->data) ? json_decode($this->data, true) : $this->data;

    // barber_id ni integer ga majburan o'zgartirish
    if (isset($data['barber_id'])) {
        $data['barber_id'] = (int) $data['barber_id'];
    }

    return [
        'id' => $this->id,
        'type' => $this->type,
        'data' => $data,
        'read_at' => $this->read_at,
        'active' => $this->active,
        'completed' => $this->completed,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
}

}

