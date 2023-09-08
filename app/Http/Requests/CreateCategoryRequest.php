<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:categories',
            'is_active' => 'nullable|boolean',
            'number' => 'nullable|integer|between:1,' . Category::count() + 1
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите название категории',
            'name.string' => 'Введите название категории в формате строки',
            'name.max' => 'Максимальная длина имени категории - 255 символов',
            'name.unique' => 'Категория с данным именем уже существует',
            'is_active.boolean' => 'Укажите, актвна ли категория, в формате true/false',
            'number.integer' => 'Введите порядковый номер категории в целочисленном формате',
            'number.between' => 'Введите порядковый номер категории в целочисленном формате от :min до :max'
        ];
    }
}
