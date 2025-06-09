<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'san_id' => 'required|exists:yard,san_id', // Kiểm tra san_id tồn tại trong bảng yard
            'name' => 'required|string|max:255', // Tên phải có giá trị, là chuỗi và không vượt quá 255 ký tự
            'phone' => 'required|string|max:15', // Số điện thoại phải có giá trị, là chuỗi và không vượt quá 15 ký tự
            'date' => 'required|date', // Ngày phải có giá trị và phải là định dạng ngày hợp lệ
            'time' => 'required|string', // Thời gian phải có giá trị và là chuỗi
            'price' => 'required|numeric', // Giá phải có giá trị và là số
            'notes' => 'nullable|string', // Ghi chú là tùy chọn và có thể là chuỗi
        ];
    }
}
