<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryFormRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100'
            ],
            'name_en' => [
                'required',
                'string',
                'min:3',
                'max:100'
            ],
            'image' => [
                'nullable',
                'image',
                'max:30000'
            ],
        ];
    }

    public function getDataWithImagePath()
    {
        $image = $this->file('image');

        $category = $this->route('product_category');

        $icon = $image ? upload_picture($image, '/images/product-categories') : (
            $category ? $category->icon : '/images/avatars/default.png'
        );

        return array_merge_recursive(
            $this->only('name', 'name_en'),
            ['icon' => $icon]
        );
    }
}
