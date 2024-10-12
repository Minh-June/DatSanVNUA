<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id' => 'required',
            'san_id' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'notes' => 'nullable|string',
            'selected_times' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'total_price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID người dùng là bắt buộc.',
            'san_id.required' => 'ID sân là bắt buộc.',
            'name.required' => 'Tên là bắt buộc.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'selected_times.required' => 'Thời gian đặt sân là bắt buộc.',
            'date.required' => 'Ngày là bắt buộc.',
            'total_price.required' => 'Giá tổng là bắt buộc.',
            'total_price.numeric' => 'Giá tổng phải là một số.',
        ];
    }
}
