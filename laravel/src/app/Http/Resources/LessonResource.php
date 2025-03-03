<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', function () {
                return LessonResource::make($this->parent);
            }),
            'children' => $this->whenLoaded('childrenRecursive', function () {
                return LessonResource::collection($this->childrenRecursive);
            }),
        ];
    }
}
