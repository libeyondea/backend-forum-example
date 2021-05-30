<?php

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;

class UpdateProfileRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_name' => 'required|unique:users,user_name,' . auth()->user()->id,
            'email' => 'required|unique:users,email,' . auth()->user()->id,
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10000'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'user_name.required' => 'User name is required',
            'email.required' => 'Email is required',
            'user_name.unique' => 'User name already exists',
            'email.unique' => 'Email already exists',
            'avatar.image' => 'Avatar must be an image file',
            'avatar.mimes' => 'Avatar file must be .png .jpg .jpeg .gif',
            'avatar.max' => 'Maximum avatar size to upload is 10000kb'
        ];
    }
}
