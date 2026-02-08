<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'max:255'
            ],
            'password' => [
                'required',
                'max:255'
            ],
            // 'email' => [
            //     'required',
            //     'email',
            //     'max:255'
            // ],
            // 'device' => [
            //     'required',
            //     'max:255'
            // ]
        ];
    }
}
