<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = session('user_id');

        return [
            'fullname'  => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'gender'    => 'required|in:Nam,Nữ,Khác',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(13)->toDateString(), // Phải ít nhất 13 tuổi
                'after_or_equal:' . now()->subYears(100)->toDateString(), // Không quá 100 tuổi
            ],
            'phonenb'   => ['required', 'regex:/^0[0-9]{9}$/', 'unique:users,phonenb,' . $userId . ',user_id'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId . ',user_id'],
        ];
    }

    public function messages()
    {
        return [
            // Họ tên
            'fullname.required' => 'Họ và tên là bắt buộc.',
            'fullname.regex'    => 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.',

            // Giới tính
            'gender.required'   => 'Giới tính là bắt buộc.',
            'gender.in'         => 'Giới tính không hợp lệ.',

            // Ngày sinh
            'birthdate.required'        => 'Ngày sinh là bắt buộc.',
            'birthdate.date'            => 'Ngày sinh không hợp lệ.',
            'birthdate.before'          => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',
            'birthdate.before_or_equal' => 'Bạn phải ít nhất đủ 13 tuổi.',
            'birthdate.after_or_equal'  => 'Tuổi tối đa được phép là 100.',

            // Số điện thoại
            'phonenb.required' => 'Số điện thoại là bắt buộc.',
            'phonenb.regex'    => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.',
            'phonenb.unique'   => 'Số điện thoại đã được sử dụng.',

            // Email
            'email.required' => 'Email là bắt buộc.',
            'email.email'    => 'Email không hợp lệ.',
            'email.max'      => 'Email không được vượt quá 255 ký tự.',
            'email.unique'   => 'Email đã được sử dụng.',
        ];
    }
}
