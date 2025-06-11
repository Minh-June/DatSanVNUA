<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Tên người dùng là bắt buộc.',
            'username.string' => 'Tên người dùng phải là một chuỗi.',
            'username.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là một chuỗi.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];
    }

    /**
     * Xử lý xác thực trong request.
     */
    public function authenticate(): void
    {
        if (!Auth::attempt([
            'username' => $this->input('username'),
            'password' => $this->input('password'),
        ])) {
            throw ValidationException::withMessages([
                'login_failed' => 'Tên đăng nhập và mật khẩu không đúng.',
            ]);
        }
    }
}
