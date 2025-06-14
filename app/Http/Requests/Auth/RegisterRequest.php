<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname'  => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'gender'    => 'required|in:Nam,Nữ,Khác',
            'birthdate' => [
                'required',
                'date',
                'before:today',
                'after_or_equal:' . now()->subYears(100)->toDateString(),
                'before_or_equal:' . now()->subYears(13)->toDateString(),
            ],
            'phonenb'   => ['required', 'regex:/^0[0-9]{9}$/', 'unique:users,phonenb'],
            'email'     => 'required|string|email|max:255|unique:users,email',
            'username'  => ['required', 'string', 'max:10', 'unique:users,username', 'regex:/^[a-zA-Z0-9]+$/'],
            'password'  => [
                'required',
                'string',
                'min:6',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            // Họ tên
            'fullname.required'     => 'Họ và tên là bắt buộc.',
            'fullname.regex'        => 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.',

            // Giới tính
            'gender.required'       => 'Giới tính là bắt buộc.',
            'gender.in'             => 'Giới tính không hợp lệ.',

            // Ngày sinh
            'birthdate.required'    => 'Ngày sinh là bắt buộc.',
            'birthdate.date'        => 'Ngày sinh không hợp lệ.',
            'birthdate.before'      => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',
            'birthdate.after_or_equal' => 'Tuổi tối đa được phép là 100.',
            'birthdate.before_or_equal' => 'Bạn phải ít nhất 13 tuổi để đăng ký.',

            // Số điện thoại
            'phonenb.required'      => 'Số điện thoại là bắt buộc.',
            'phonenb.unique'        => 'Số điện thoại đã được sử dụng.',
            'phonenb.regex'         => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.',

            // Email
            'email.required'        => 'Email là bắt buộc.',
            'email.email'           => 'Email không hợp lệ.',
            'email.max'             => 'Email không được vượt quá 255 ký tự.',
            'email.unique'          => 'Email đã được sử dụng.',

            // Tên đăng nhập
            'username.required'     => 'Tên người dùng là bắt buộc.',
            'username.unique'       => 'Tên người dùng đã được sử dụng.',
            'username.regex'        => 'Tên người dùng chỉ được chứa chữ cái và số, không chứa dấu cách hoặc ký tự đặc biệt.',
            'username.max'          => 'Tên người dùng không được vượt quá 10 ký tự.',

            // Mật khẩu
            'password.required'     => 'Mật khẩu là bắt buộc.',
            'password.min'          => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max'          => 'Mật khẩu không được vượt quá 100 ký tự.',
            'password.regex'        => 'Mật khẩu phải có ít nhất 1 chữ thường, 1 chữ in hoa và 1 chữ số.',
        ];
    }
}
