<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamGroupRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

         $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp';
         $rules["team_id"] = 'required|exists:teams,id';
        foreach (languages() as $language) {
            
            $rules["title.$language->abbreviation"] = 'required';
        }
        return  $rules;
    }
}
