<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointsResource extends JsonResource
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
            'type'=>$this->type,
            'type_trans'=>$this->type == 1 ? 'increment point' : 'decrement points',
            'points'=>$this->points,
            'action'=>helperTrans("api.$this->action"),
        ];
    }

    // public function with($request)
    // {
    //     return [
    //         'pagination' => [
    //             'current_page' => $this->currentPage(),
    //             'last_page' => $this->lastPage(),
    //             'per_page' => $this->perPage(),
    //             'total' => $this->total(),
    //             'next_page_url' => $this->nextPageUrl(),
    //             'prev_page_url' => $this->previousPageUrl(),
    //         ],
    //     ];
    // }

}
