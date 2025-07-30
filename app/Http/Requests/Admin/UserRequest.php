<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
      
            return [
                'first_name' => 'required',
                'last_name' => 'nullable',
                'email' => "required|unique:users,email,".$this->id,               
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'password' => 'nullable|min:6',
                'is_active'=>'nullable|in:0,1',
                'country_id' => 'required|exists:countries,id',
                'city_id' => 'required|exists:cities,id',
                'club_id' => 'required|exists:clubs,id',

            ];
    }
}
