<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $userID = $this->route('id');

        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:60',
            'content' => 'required|string',
        ];
    }
}
