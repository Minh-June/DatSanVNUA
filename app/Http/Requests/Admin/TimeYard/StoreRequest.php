<?php

namespace App\Http\Requests\Admin\TimeYard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Time;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yard_id' => 'required|exists:yards,yard_id',
            'time'    => ['required', 'regex:/^\d{2}:\d{2}\s*-\s*\d{2}:\d{2}$/'],
            'price'   => 'required|numeric|min:0',
            'date'    => 'required|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'time.regex' => 'Định dạng khung giờ phải là HH:MM - HH:MM (VD: 06:00 - 07:30)',
            'price.numeric' => 'Giá tiền phải là số.',
            'yard_id.required' => 'Vui lòng chọn sân.',
            'date.required' => 'Vui lòng chọn ngày.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = Time::where('yard_id', $this->yard_id)
                ->whereDate('date', $this->date)
                ->where('time', $this->time)
                ->exists();

            if ($exists) {
                $validator->errors()->add('time', 'Khung giờ này đã tồn tại, vui lòng chọn khung giờ khác!');
            }
        });
    }
}
