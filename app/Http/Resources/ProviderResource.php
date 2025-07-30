<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'phone'=>$this->phone,
            'address'=>$this->address,
            'type'=>$this->type,
            'image'=>get_file($this->image),
            'role'=>$this->role,
            'avg_rate'=>$this->avg_rate,
            'rate_count'=>$this->rate_count,
            'city'=>new CityResource($this->whenLoaded('city')),
            'service'=>new ServiceResource($this->whenLoaded('service')),
            'provider'=>new ProviderResource($this->whenLoaded('provider')),
            'provider_prices'=>ProviderPriceResource::collection($this->whenLoaded('provider_price')),

            'day_hours' =>ProviderDayHourResource::collection($this->day_hours),

        ];
    }
}
