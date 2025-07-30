<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VarResource extends JsonResource
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
            'desc'=>$this->desc,
            'media_type'=>$this->media_type == 1 ? 'image' : 'video',
            'media'=>get_file($this->media),
            'choices'=>VarChoiceResource::collection($this->whenLoaded('choices')),
        ];
    }
}
