<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPlanResource extends JsonResource
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
            'type'=>$this->type(),
            'user_name'=>$this->user?$this->user->fullName() : null,
            'is_user_liked'=>$this->checkIfAuthUserLiked(),
            'create_date'=> formatTimeDifference($this->created_at),
            'comments'=>UserPalnCommentResource::collection($this->whenLoaded('comments')),
            'count_comments'=>$this->countComments(),
            'count_likes'=>$this->countLikes(),
            'is_plan'=>true,
            'players'=>PlanResource::collection($this->whenLoaded('plans')),
        ];
    }
}
