<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VarsRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

         $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp';

        foreach (languages() as $language) {
            
            $rules["desc.$language->abbreviation"] = 'required';
        }
 
        return  $rules;
    }
}
