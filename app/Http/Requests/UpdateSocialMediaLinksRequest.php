<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaLinksRequest extends FormRequest
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
        if (!$this->isMethod('POST')) {
            return [];
        }
        return [
            'social_media_links' => [
                'required',
                'array'
            ],
            'social_media_links.*' => [
                'nullable',
                'url'
            ]
        ];
    }

    public function messages()
    {
        return [
            'social_media_links.*.url' => 'هذا الرابط يحب ان يكون عنوان URL صالح.'
        ];
    }
}
