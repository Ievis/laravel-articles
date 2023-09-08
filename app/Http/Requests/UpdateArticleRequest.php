<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
        $category_id = request()->input('category_id');
        $category = Category::find($category_id);
        $articles_count = $category?->articles()?->count();

        return [
            'name' => 'required|string|max:255|unique:articles,name,' . request('article')->id,
            'slug' => 'required|string|max:255|unique:articles,name,' . request('article')->id,
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'image|size:15360',
            'is_active' => 'nullable|boolean',
            'number_in_category' => 'nullable|integer|between:1,' . $articles_count
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
            'slug.required' => 'Введите slug статьи',
            'slug.string' => 'Введите slug статьи в формате строки',
            'slug.max' => 'Максимальная длина slug\'а - 255 символов',
            'slug.unique' => 'Статья с данным slug\'ом уже существует',
            'category_id.required' => 'Укажите категорию для публикуемой статьи',
            'category_id.integer' => 'Укажите категорию для публикуемой статьи в целочисленном формате',
            'category_id.exists' => 'Укажите существующую категорию для публикуемой статьи',
            'image.image' => 'Загрузите фото статьи',
            'image.size' => 'Загрузите фото статьи с размером не более 15 Мб',
            'is_active.boolean' => 'Укажите, актвна ли статья, в формате true/false',
            'number_in_category.integer' => 'Введите порядковый номер статьи в категории в целочисленном формате',
            'number_in_category.between' => 'Введите порядковый номер статьи в категории в целочисленном формате от :min до :max'
        ];
    }
}
