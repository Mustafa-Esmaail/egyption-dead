<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

        foreach (languages() as $language) {
            $rules["title.$language->abbreviation"] = 'required';
        }
 


        return  $rules;
    }
}
