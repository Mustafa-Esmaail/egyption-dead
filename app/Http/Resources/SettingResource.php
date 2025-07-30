<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'whatsApp'=>$this->whatsApp,
            'instagram'=>$this->instagram,
            'x'=>$this->twitter,
            'facebook'=>$this->facebook,
            // 'name'=>$this->name,
            'logo'=>get_file($this->logo_header),
            'icon'=>get_file($this->fave_icon),
        ];
    }
}
