<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AcademyRequest extends FormRequest
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
         $rules['city_id'] = 'required|exists:cities,id';
         $rules['country_id'] = 'required|exists:countries,id';

        foreach (languages() as $language) {
            $rules["title.$language->abbreviation"] = 'required|min:3|string';
            $rules["desc.$language->abbreviation"] = 'required|min:3|string';
        }



        return  $rules;
    }
}
