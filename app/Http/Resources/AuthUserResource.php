<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'points'=>$this->points,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'type'=>$this->type,
            'image'=>get_file($this->image),
            'token'=>$this->token,
            'new_user'   => $this->when($this->new_user, $this->new_user,false),
            'team'=>TeamResource::collection($this->whenLoaded('teams')),

        ];
    }
}
