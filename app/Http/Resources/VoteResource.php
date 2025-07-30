<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>(int)$this->id,
            'title'=>$this->title,
            'desc'=>$this->desc,
            // 'choices'=>new VoteChoiceResource($this->whenLoaded('choices')),
            'choices' => VoteChoiceResource::collection($this->whenLoaded('choices')),
            // 'image'=>get_file($this->image),
        ];
    }
}
