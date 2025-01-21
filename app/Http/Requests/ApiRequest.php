<?php

namespace App\Http\Requests;

use App\Exceptions\ApiValidateException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
abstract class ApiRequest extends FormRequest
{

    public function rules(): array
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiValidateException(422, false, 'Validation error', $validator->errors());
    }
}
