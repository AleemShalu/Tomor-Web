<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => [
                'type' => $this->data['type'] ?? null,
                'title' => $this->data['title'] ?? null,
                'message' => $this->data['message'] ?? null,
                'sender_id' => $this->data['sender_id'] ?? null,
                'recipient_id' => $this->data['recipient_id'] ?? null,
            ],
            'read_at' => convertDateToRiyadhTimezone($this->read_at),
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
