<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AwardRedeemRequestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'award_id' => 'required|exists:awards,id',
            'user_points' => 'required|integer|min:0',
            'status' => 'required|in:pending,approved,rejected'
        ];
    }

    public function validationData()
    {
        return $this->all();
    }
}
