<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  Request  $request
   * @return array
   */
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'total_members' => $this->members->count(),
      'slug' => $this->slug,
      'designs' => DesignResource::collection($this->designs),
      'owner' => new UserResource($this->owner),
      'member' => UserResource::collection($this->members)
    ];
  }
}
