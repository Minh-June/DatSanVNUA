<?php

namespace App\Http\Requests\Admin\PayInfor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // cho phép mọi user đã login
    }

    public function rules(): array
    {
        return [
            'acc_name'   => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'], // chỉ chữ và khoảng trắng
            'acc_number' => ['required', 'digits_between:6,20'], // chỉ số, 6-20 chữ số
            'acc_type'   => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'], // chỉ chữ và khoảng trắng
            'qr_code'    => ['nullable', 'image', 'max:2048'], // optional, ảnh max 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'acc_name.required'   => 'Vui lòng nhập tên tài khoản.',
            'acc_name.regex'      => 'Tên tài khoản chỉ được nhập chữ.',
            'acc_name.max'        => 'Tên tài khoản quá dài.',

            'acc_number.required' => 'Vui lòng nhập số tài khoản.',
            'acc_number.digits_between' => 'Số tài khoản phải là số, từ 6 đến 20 chữ số.',

            'acc_type.required'   => 'Vui lòng nhập ngân hàng.',
            'acc_type.regex'      => 'Ngân hàng chỉ được nhập chữ.',
            'acc_type.max'        => 'Tên ngân hàng quá dài.',

            'qr_code.image'       => 'File QR phải là hình ảnh.',
            'qr_code.max'         => 'File QR quá lớn (tối đa 2MB).',
        ];
    }
}
