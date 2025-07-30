<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            // 'video' => 'nullable',
            'price' => 'required|numeric',
            'user_id'=>'required|exists:users,id',
        ];

        foreach (languages() as $language) {
            $rules["title.$language->abbreviation"] = 'required|min:3|string';
            $rules["desc.$language->abbreviation"] = 'required|min:3|string';
            // $rules["address.$language->abbreviation"] = 'required|min:3|string';
        }


        return $rules;
    }

}
