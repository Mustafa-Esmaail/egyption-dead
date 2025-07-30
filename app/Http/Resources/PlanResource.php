<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'title'=>$this->player ? $this->player->title:null,
            'number'=>$this->player ? $this->player->number:null,
            'image'=>$this->player ? get_file($this->player->image):null,
            'player_id'=>$this->player_id,
            'postion_x'=>$this->postion_x ?? 0,
            'postion_y'=>$this->postion_y ?? 0,
        ];
    } 
}
