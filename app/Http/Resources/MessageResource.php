<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'deleted' => $this->trashed(),
            'dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'sender' => new UserResource($this->sender)
        ];
    }
}
