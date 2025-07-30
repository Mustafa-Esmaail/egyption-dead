<?php

namespace App\Http\Requests\Api\Patient;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
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
            'email' => 'required|exists:patients',
            'password' => 'required',
        ];


    }

    public function messages(): array
    {
        return [
            'password.required' => helperTrans('api.The Password is required.'),
            'email.required' => helperTrans('api.The Email is required.'),
            'email.exists' => helperTrans('api.The Email is not registered'),
        ];
    }
}
