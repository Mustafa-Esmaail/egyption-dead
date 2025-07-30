<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamPlayerResource extends JsonResource
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
            'number'=>$this->number,
            'image'=>get_file($this->image),
            'player_id'=>(int)$this->id,
            'postion_x'=> 0,
            'postion_y'=>0,
        ];
    }
}
