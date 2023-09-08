<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:categories,name' . request('category')->id,
            'is_active' => 'nullable|boolean',
            'number' => 'nullable|integer|between:1,' . Category::count()
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите название статьи',
            'name.string' => 'Введите название статьи в формате строки',
            'name.max' => 'Максимальная длина имени - 255 символов',
            'name.unique' => 'Статья с данным именем уже существует',
            'is_active.boolean' => 'Укажите, актвна ли статья, в формате true/false',
            'number.integer' => 'Введите порядковый номер статьи в целочисленном формате',
            'number.between' => 'Введите порядковый номер статьи в целочисленном формате от :min до :max'
        ];
    }
}
