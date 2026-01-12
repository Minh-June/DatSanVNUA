<?php

namespace App\Http\Requests\Admin\TimeYard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yard_id'        => 'required|exists:yards,yard_id',
            'start'          => 'required|date_format:H:i',
            'end'            => 'required|date_format:H:i|after:start',
            'price_weekday'  => 'nullable|numeric|min:0',
            'price_weekend'  => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'yard_id.required'      => 'Vui lòng chọn sân.',
            'yard_id.exists'        => 'Sân không tồn tại.',

            'start.required'        => 'Vui lòng chọn giờ bắt đầu.',
            'start.date_format'     => 'Định dạng giờ bắt đầu phải là HH:MM.',

            'end.required'          => 'Vui lòng chọn giờ kết thúc.',
            'end.date_format'       => 'Định dạng giờ kết thúc phải là HH:MM.',
            'end.after'             => 'Giờ kết thúc phải lớn hơn giờ bắt đầu.',

            'price_weekday.numeric' => 'Giá T2-T6 phải là số.',
            'price_weekend.numeric' => 'Giá T7-CN phải là số.',
        ];
    }
}
