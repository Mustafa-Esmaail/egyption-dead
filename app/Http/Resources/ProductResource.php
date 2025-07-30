<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'phone'=>$this->phone,
            'desc'=>$this->desc,
            'price'=>$this->price,
            'address'=>$this->address,
            'image'=>get_file($this->image),
            'category'=>new ProductCategoryResource($this->whenLoaded('category')),
        ];
    }
}
