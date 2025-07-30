<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'message'=>$this->message,
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
