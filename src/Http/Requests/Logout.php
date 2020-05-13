<?php

namespace YoAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Logout extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}