<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTalantResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'user_name' => $this->user ? $this->user->fullName() : null,
            'image' => $this->user?->image ? get_file($this->user?->image) : null,
            'phone' => $this->phone,
            'age' => $this->age,
            'address' => $this->address,
            'persone_heigth' => $this->persone_heigth,
            'title' => $this->title,
            'is_featured'=>$this->is_featured,
            'persone_weight' => $this->persone_weight,
            'country' => new CountryResource($this->whenLoaded('country')),
            'city' => new CityResource($this->whenLoaded('city')),
            'is_recommended' => $this->recommended,
            'video' => $this->video ? get_file($this->video) : null,
            'user_rate' => $this->userRate(),
            'count_rates' => $this->rates ? $this->rates->count('rate') : null,
            'avg_rates' => $this->rates ? number_format($this->rates->avg('rate'), 1) : null,
            'comment_count' => $this->countComments(),
            'likes' => $this->countLikes(),
            'is_user_liked' => $this->checkIfAuthUserLiked($this->id),
            'is_user_fav' => $this->checkIfAuthUserFav($this->id),
            'shares' => $this->countShares(),
            'create_date' =>  $this->createdSince(),
            'comments' => $this->whenLoaded('comments', function () {
                return UserTalantCommentResource::collection(
                    $this->comments->sortByDesc('created_at')
                );
            }),
        ];
    }
}
