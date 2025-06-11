<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép thực hiện request
    }

    public function rules(): array
    {
        return [
            'matkhau_hientai'    => 'required',
            'matkhau_moi'        => 'required|min:6',
            'xacnhan_matkhau'    => 'required|same:matkhau_moi',
        ];
    }

    public function messages(): array
    {
        return [
            'matkhau_hientai.required'  => 'Vui lòng nhập mật khẩu hiện tại.',
            'matkhau_moi.required'      => 'Vui lòng nhập mật khẩu mới.',
            'matkhau_moi.min'           => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'xacnhan_matkhau.required'  => 'Vui lòng xác nhận mật khẩu mới.',
            'xacnhan_matkhau.same'      => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
