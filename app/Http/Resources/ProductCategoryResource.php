<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        // $currentData=['id'=>null,'title'=>helperTrans('api.All')];


        return [
            'id'=>(int)$this->id,
            'title'=>$this->title,
            'image'=>get_file($this->image),
        ];

        // return $allData;
        // return $Alldata;
    }
}
