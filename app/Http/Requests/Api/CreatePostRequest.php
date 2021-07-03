<?php

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;

class CreatePostRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'slug' => 'unique:post',
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5000',
            'tags' => 'required|array|min:1|max:6',
            'tags.*.slug' => 'required|string|max:33'
        ];
    }

    public function messages()
    {
        return [
            'slug.unique' => 'Slug already exists',
            'title.required' => 'Title is required',
            'content.required' => 'Content is required',
            'category_id.required' => 'Category_id is required',
            'image.image' => 'Image must be an image file',
            'image.mimes' => 'Image file must be .png .jpg .jpeg .gif',
            'image.max' => 'Maximum image size to upload is 5000kb',
            'tags.required' => 'Tag is required',
            'tags.array' => 'Tag must be an array',
            'tags.min' => 'Tag must have an item',
            'tags.max' => 'Add up to 6 tags'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tags' => json_decode($this->tags, true),
            'slug' => Str::slug($this->title, '-') . '-' . Str::lower(Str::random(4))
        ]);
    }
}
