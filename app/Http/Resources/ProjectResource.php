<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
    */
        public function toArray(Request $request): array
        {
            return [
                'id'          => $this->id,
                'name'        => $this->name,
                'description' => $this->description,
                'created_by'  => $this->whenLoaded('creator', fn() => $this->creator->name),
                'members'     => UserResource::collection($this->whenLoaded('members')),
            ];
        }

}
