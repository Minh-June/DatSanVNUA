<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matkhau_hientai'   => 'required',
            'matkhau_moi'       => [
                'required',
                'min:6',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'different:matkhau_hientai'
            ],
            'xacnhan_matkhau'   => 'required|same:matkhau_moi',
        ];
    }

    public function messages(): array
    {
        return [
            // Mật khẩu hiện tại
            'matkhau_hientai.required'   => 'Vui lòng nhập mật khẩu hiện tại.',

            // Mật khẩu mới
            'matkhau_moi.required'       => 'Vui lòng nhập mật khẩu mới.',
            'matkhau_moi.min'            => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'matkhau_moi.max'            => 'Mật khẩu mới không được vượt quá 100 ký tự.',
            'matkhau_moi.regex'          => 'Mật khẩu mới phải có ít nhất 1 chữ thường, 1 chữ in hoa và 1 chữ số.',
            'matkhau_moi.different'      => 'Mật khẩu mới phải khác mật khẩu hiện tại.',

            // Xác nhận mật khẩu
            'xacnhan_matkhau.required'   => 'Vui lòng xác nhận mật khẩu mới.',
            'xacnhan_matkhau.same'       => 'Xác nhận mật khẩu không khớp với mật khẩu mới.',
        ];
    }
}
