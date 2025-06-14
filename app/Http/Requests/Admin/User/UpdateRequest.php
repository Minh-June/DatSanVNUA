<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền gửi request này hay không.
     */
    public function authorize(): bool
    {
        return true; // Cho phép thực thi request
    }

    /**
     * Các quy tắc xác thực áp dụng cho request.
     */
    public function rules(): array
    {
        return [
            'keyword' => [
                'required',
                'regex:/^[a-zA-ZÀ-ỹ\s\-]+$/u', // Chỉ cho phép chữ cái, khoảng trắng, và dấu gạch nối
            ],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     */
    public function messages(): array
    {
        return [
            'keyword.required' => 'Vui lòng nhập từ khóa tìm kiếm.',
            'keyword.regex' => 'Từ khóa không được chứa số hoặc ký tự đặc biệt.',
        ];
    }
}
