<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteChoiceResource extends JsonResource
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
            'rate'=> $this->rates ? number_format($this->choicePrecent(),2) : 0,
            'image'=>get_file($this->image),
            'is_user_selected'=> $this->checkIfUserSelected(),

        ];
    }
}
