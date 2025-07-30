<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamPlayerRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

         $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp';
         $rules['team_id'] = 'required|exists:teams,id';
         $rules['number'] = 'required';

        foreach (languages() as $language) {
            $rules["title.$language->abbreviation"] = 'required';
        }
 


        return  $rules;
    }
}
