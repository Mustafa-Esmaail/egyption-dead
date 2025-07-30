<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
            'name'=>$this->name,
            'country'=>CountryResource::make($this->whenLoaded('country')),
            'city'=>CityResource::make($this->whenLoaded('city')),
        ];
    }
}
