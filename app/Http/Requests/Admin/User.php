<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
{
    /**
     * Xác định người dùng có quyền gửi yêu cầu này không.
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
                'regex:/^[a-zA-ZÀ-ỹ\s\-]+$/u', // Chỉ cho phép chữ cái và khoảng trắng
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
