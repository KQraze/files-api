<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function rules(): array {
        return [
            'files' => 'required',
            'files.*' => ['max:2048', 'mimes:doc,pdf,docx,zip,jpeg,jpg,png', 'required'],
        ];
    }
}
