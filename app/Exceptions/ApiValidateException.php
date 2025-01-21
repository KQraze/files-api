<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiValidateException extends HttpResponseException
{
    public function __construct($code = 422, $success = false, $message = 'Validation error', $errors = []) {
        $data = [
            'success' => $success,
            'code' => $code
        ];

        if (count($errors) > 0) {
            $data['message'] = $errors;
        } else {
            $data['message'] = $message;
        }

        parent::__construct(response()->json($data)->setStatusCode($code, $message));
    }
}
