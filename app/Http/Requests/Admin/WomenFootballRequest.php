<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WomenFootballRequest extends FormRequest
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


        $rules = [
            'women_football_category_id' => 'nullable|exists:women_football_categories,id',
            'image' => 'nullable',
           'video' => 'nullable|mimes:mp4',
        ];
        
        foreach (languages() as $language) {
            $rules["title.$language->abbreviation"] = 'required';
            $rules["desc.$language->abbreviation"] = 'required';
            $rules["short_desc.$language->abbreviation"] = 'required';
        }
 

        return $rules;
    }

}
