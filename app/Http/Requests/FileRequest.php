<?php

namespace App\Http\Requests;


class FileRequest extends ApiRequest
{
    public function rules(): array {
        return [
            'files' => ['required'],
            'files.*' => ['max:2048', 'mimes:doc,pdf,docx,zip,jpeg,jpg,png', 'required'],
        ];
    }
}
