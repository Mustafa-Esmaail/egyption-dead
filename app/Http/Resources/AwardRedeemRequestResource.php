<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AwardRedeemRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'award_id' => $this->award_id,
            'status' => $this->status,
            'user_points' => $this->user_points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'award' => new AwardResource($this->whenLoaded('award')),
        ];
    }
}
