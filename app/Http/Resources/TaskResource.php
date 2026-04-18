<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
        'title'       => $this->title,
        'status'      => $this->status,
        'project'     => $this->whenLoaded('project', fn() => [
            'id'   => $this->project->id,
            'name' => $this->project->name,
        ]),
        'assigned_to' => $this->whenLoaded('assignee', fn() => [
            'id'   => $this->assignee?->id,
            'name' => $this->assignee?->name,
        ]),
        'created_at'  => $this->created_at->toDateTimeString(),
    ];
}
}
