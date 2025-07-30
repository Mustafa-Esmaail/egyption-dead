<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderDayHourResource   extends JsonResource
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
            'day_title' => $this->day ? $this->day->title : null,
            'from_time'=>$this->from_time,
            'to_time'=>$this->to_time,

        ];
    }
}
