<?php

namespace App\Http\Resources;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamGroupResource extends JsonResource
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
            'image'=>get_file($this->image),
            // 'is_user_favourite'=>UserFavouriteTeamAndPlayer::where('user_id',auth()->user()->id)->where('foriegn_key',$this->id)->exists(),
            'is_user_group'=>UserGroup::where('user_id',userApi()->user()->id)->where('team_group_id',$this->id)->exists(),
        ];
    }
}
