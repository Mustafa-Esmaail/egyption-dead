<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthProviderResource extends JsonResource
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
            'first_name'=>$this->name,
            'last_name'=>$this->last_name,
            'phone'=>$this->phone,
            'address'=>$this->address,
            'type'=>$this->type,
            'image'=>get_file($this->image),
            'role'=>$this->role,
            'token'=>$this->token,
            'city'=>new CityResource($this->whenLoaded('city')),
            'service'=>new ServiceResource($this->whenLoaded('service')),
            'provider'=>new ProviderResource($this->whenLoaded('provider')),

        ];
    }
}
