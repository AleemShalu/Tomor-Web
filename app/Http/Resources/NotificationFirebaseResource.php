<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationFirebaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => [
                'title' => $this->data['title'] ?? '',
                'body' => $this->data['body'] ?? '',
            ],
            'read_at' => convertDateToRiyadhTimezone($this->read_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
