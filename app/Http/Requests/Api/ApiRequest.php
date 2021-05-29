<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
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
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'error' => [
                        'type' => '',
                        'title' => 'Your request parameters did not validate',
                        'status' => 200,
                        'invalid_params' => $errors,
                        'detail' => 'Your request parameters did not validate',
                        'instance' => ''
                    ]
                ], 200)
            );
        }
        parent::failedValidation($validator);
    }
}
