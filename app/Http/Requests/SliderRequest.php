<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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

    /**sss
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'name' => 'required|string|max:50',
            'text' => 'required|string|max:50',
            'text_en' => 'required|string|max:50',
            'text_color' => 'required|string|max:50',
            'url' => 'reuired',
            'image' => 'required|string|max:255',
            'active' => 'required'

        ];
    }
}
