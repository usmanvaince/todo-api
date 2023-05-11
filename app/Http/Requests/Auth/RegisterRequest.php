<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string' , 'email', 'unique:users,email', 'max:255'],
            'password' => ['required','string', 'min:8', 'confirmed']
        ];
    }
}
