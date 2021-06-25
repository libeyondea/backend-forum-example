<?php

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;

class UpdatePostRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => 'unique:post',
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5000',
            'tags' => 'required|array|min:1|max:4',
            'tags.*.slug' => 'required|string',
            'is_remove_img' => 'required|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
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
            'tags.max' => 'Add up to 4 tags',
            'is_remove_img.boolean' => 'isRemoveImage must be a boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'tags' => json_decode($this->tags, true),
            'slug' => Str::slug($this->title, '-') . '-' . Str::lower(Str::random(4)),
            'is_remove_img' => filter_var($this->is_remove_img, FILTER_VALIDATE_BOOLEAN)
        ]);
    }
    }
