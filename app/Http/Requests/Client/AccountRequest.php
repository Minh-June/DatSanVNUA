<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'phonenb' => 'required|string|max:10|regex:/^[0-9]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . session('user_id') . ',user_id',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Họ và tên là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'birthdate.required' => 'Ngày sinh là bắt buộc.',
            'phonenb.required' => 'Số điện thoại là bắt buộc.',
            'phonenb.regex' => 'Số điện thoại chỉ được chứa các ký tự số.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã được sử dụng.',
        ];
    }
}
