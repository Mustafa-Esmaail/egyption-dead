<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WomenFootballResource extends JsonResource
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
            'short_desc'=>$this->short_desc,
            'desc'=>$this->desc,
              'video'=> $this->video ? get_file($this->video) : null,
            'image'=>get_file($this->image),
            // 'subscribers'=>$this->countSubscribers(),
            'likes'=>$this->countLikes(),
            'is_user_liked'=>$this->check_auth_action(1),
            'create_date'=> formatTimeDifference($this->created_at),
        ];
    }
}
