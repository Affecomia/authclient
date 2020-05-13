<?php

namespace YoAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowUser extends FormRequest
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