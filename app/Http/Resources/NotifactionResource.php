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
        // Agar data string bo'lsa, arrayga decode qilish
        $data = is_string($this->data) ? json_decode($this->data, true) : $this->data;

        // barber_id ni integerga o'tkazish (majburiy)
        if (isset($data['barber_id'])) {
            $data['barber_id'] = (int) $data['barber_id'];
        }

        // end_time ni hisoblash
        if (isset($data['booking_time']) && isset($data['minut'])) {
            $startTime = $data['booking_time'];
            $durationMinutes = (int) $data['minut'];

            $endTimestamp = strtotime($startTime) + ($durationMinutes * 60);
            $data['end_time'] = date('Y-m-d H:i:s', $endTimestamp);
        } else {
            $data['end_time'] = null;
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

