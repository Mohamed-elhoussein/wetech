<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
                'max:100',
                'unique:skills,name,' . optional($this->skill)->id . ',id'
            ]
        ];
    }

    public function messages(){
        return [
            'name.required' => 'حقل الإسم مطلوب.',
        ];
    }
}
