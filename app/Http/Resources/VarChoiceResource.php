<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VarChoiceResource extends JsonResource
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
            'title'=>$this->choose,
            'rate'=> $this->rates ? number_format($this->choicePrecent(),2) : 0,
            'is_user_selected'=> $this->checkIfUserSelected(),
            // 'image'=>get_file($this->image),
        ];
    }
}
