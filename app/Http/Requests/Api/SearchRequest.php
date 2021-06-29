<?php

namespace App\Http\Requests\Api;

class SearchRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'search_fields' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'search_fields.required' => 'Search fields is required'
        ];
    }
}
