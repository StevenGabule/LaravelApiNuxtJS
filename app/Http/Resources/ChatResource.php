<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'is_unread' => $this->isUnreadForUser(auth()->id()),
            'latest_message' => new MessageResource($this->latest_message),
            'participants' => UserResource::collection($this->participants),
        ];
    }
}
