<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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

        // dd(request());
        $baseRules = [
            'first_name' => 'required',
            'last_name' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'email' => 'required|email|unique:admins,email' . ($this->id ? ",{$this->id}" : ''),
            'phone' => 'required|unique:admins,phone' . ($this->id ? ",{$this->id}" : ''),
            'password' => ($this->id ? 'nullable' : 'required') . '|min:6',
            'roles'=>'required|exists:roles,id',
        ];

        return $baseRules;
    }

}
