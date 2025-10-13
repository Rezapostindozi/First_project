<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()

    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ];
    }






}
