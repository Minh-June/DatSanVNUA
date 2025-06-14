<?php

namespace App\Http\Requests\Admin\Yard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'required|exists:types,type_id',
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}0-9\s\-\(\)]+$/u', // Cho phép chữ, số, khoảng trắng, -, (, )
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sân không được để trống.',
            'name.regex' => 'Tên sân không được chứa ký tự đặc biệt.',
            'type_id.required' => 'Vui lòng chọn thể loại sân.',
            'type_id.exists' => 'Thể loại sân không hợp lệ.',
        ];
    }
}
