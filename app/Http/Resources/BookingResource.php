<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'request_type_date'=>$this->request_type_date,
            'request_type'=>$this->request_type,
            'status'=>$this->status,
            'discount'=>$this->discount,
            'coupon'=>$this->coupon,
            'price'=>$this->price,
            'owner_car_phone'=>$this->owner_car_phone,
            'address'=>$this->address,
            'type'=>new TypeResource($this->whenLoaded('type')),
            'city'=>new CityResource($this->whenLoaded('city')),
            'provider_price'=>new ProviderPriceResource($this->whenLoaded('provider_price')),
            'Vehicle'=>new VehicleResource($this->whenLoaded('Vehicle')),

        ];
    }
}
