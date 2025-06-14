<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InforRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        return [
            'fullname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u' // Chỉ chứa chữ và khoảng trắng
            ],
            'gender' => 'required|in:Nam,Nữ,Khác',
            'birthdate' => 'required|date',
            'phonenb' => [
                'required',
                'regex:/^0\d{9}$/', // Bắt đầu bằng 0, đủ 10 chữ số
                'unique:users,phonenb,' . $userId . ',user_id', //Kiểm tra trùng ngoại trừ bản ghi hiện tại
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId . ',user_id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Họ và tên là bắt buộc.',
            'fullname.regex' => 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'birthdate.required' => 'Ngày sinh là bắt buộc.',
            'phonenb.required' => 'Số điện thoại là bắt buộc.',
            'phonenb.regex' => 'Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số.',
            'phonenb.unique' => 'Số điện thoại đã được sử dụng.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
        ];
    }
}
