<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderPriceResource extends JsonResource
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
            'price'=>$this->price,
            'type'=>new TypeResource($this->whenLoaded('type')),
            'model'=>new ModelResource($this->whenLoaded('model')),
            'make'=>new MakeResource($this->whenLoaded('make')),
        ];
    }
}
