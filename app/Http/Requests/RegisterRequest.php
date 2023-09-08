<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    public function messages()
    {
        return [
            'email.required' => 'Введите email',
            'email.email' => 'Введите email',
            'email.max' => 'Максимальная длина email\'а - 255 символов',
            'email.unique' => 'Данный email уже занят',
            'password.required' => 'Введите пароль',
            'password.min' => 'Минимальная длина пароля - 6 символов',
            'password.max' => 'Максимальная длина пароля - 255 символов',
            'password.confirmed' => 'Введённые пароли не совпадают'
        ];
    }
}
