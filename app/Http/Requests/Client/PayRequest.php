<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.image'      => 'File tải lên phải là hình ảnh.',
            'images.*.mimes'      => 'Hình ảnh phải có định dạng jpeg, jpg hoặc png.',
            'images.*.max'        => 'Hình ảnh không được vượt quá 2MB.',
        ];
    }
}
