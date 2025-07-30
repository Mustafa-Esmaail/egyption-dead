<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPalnCommentResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'=>(int)$this->id,
            'user_name'=>$this->user ? $this->user->fullName() : null,
            'comment'=>$this->comment,
            'create_date'=> formatTimeDifference($this->created_at),
        ];
    }
}
