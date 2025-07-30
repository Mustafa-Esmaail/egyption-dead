<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyResource extends JsonResource
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
            'city_id'=>$this->city_id,
            'country_id'=>$this->country_id,
            'image'=>get_file($this->image),
            'subscribers'=>$this->countSubscribers(),
            'is_user_subscrib'=>$this->check_auth_action(2),
            'is_user_liked'=>$this->check_auth_action(1),
            'likes'=>$this->countLikes(),
            'create_date'=> helperTrans('api.since ') . $this->createdSince(),
            'city'=>new CityResource($this->whenLoaded('city')),
            'country'=>new CountryResource($this->whenLoaded('country')),
        ];
    }
}
