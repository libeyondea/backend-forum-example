<?php

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;

class CreateCommentRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'content' => 'required',
            'post_slug' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Content is required',
            'post_slug.required' => 'post_slug is required'
        ];
    }
}
