<?php

namespace YoAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Refresh extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }
}