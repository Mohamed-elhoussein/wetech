<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
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
            "username" => [
                "required",
                "string",
                "min:3",
                "max:100",
                "unique:users,username," . $this->user->id . ',id'
            ],
            "email" => [
                "required",
                "email",
                "string",
                "min:3",
                "max:100",
                "unique:users,email," . $this->user->id . ',id'
            ],
            "number_phone" => [
                "required",
                "string",
                "min:7",
                "max:25",
                "unique:users,number_phone," . $this->user->id . ',id'
            ],
            "country_id" => [
                "required",
                "string",
                "min:3",
                "max:100",
                "exists:countries,id"
            ],
            "avatar" => [
                "nullable",
                "image",
                "max:30000",
            ],
            "first_name" => [
                "nullable",
                "string",
                "min:3",
                "max:100",
            ],
            "second_name" => [
                "nullable",
                "string",
                "min:3",
                "max:100",
            ],
            "last_name" => [
                "nullable",
                "string",
                "min:3",
                "max:100",
            ],
            "friend_number" => [
                "nullable",
                "string",
                "min:7",
                "max:25",
            ],
            "identity" => [
                "nullable",
                "image",
                "min:3",
                "max:100",
                "max:30000",
            ],
        ];
    }

    public function getUpdatedFields()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'number_phone' => $this->number_phone,
            'friend_number' => $this->friend_number,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'last_name' => $this->last_name,
            'friend_number' => $this->friend_number,
            'country_id' => $this->country_id,
            'avatar' => $this->avatar ? upload_picture($this->file('avatar'), '/images/avatars') : $this->user->avatar,
            'identity' => $this->avatar ? upload_picture($this->file('identity'), '/images/identity') : $this->user->identity,
        ];
    }
}
