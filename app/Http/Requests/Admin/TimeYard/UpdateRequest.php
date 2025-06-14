<?php

namespace App\Http\Requests\Admin\TimeYard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Time;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yard_id' => 'required|exists:yards,yard_id',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'time' => [
                'required',
                'regex:/^\d{2}:\d{2}\s*-\s*\d{2}:\d{2}$/'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $time_id = $this->route('time_id');

            $exists = Time::where('yard_id', $this->yard_id)
                ->where('date', $this->date)
                ->where('time', $this->time)
                ->where('time_id', '!=', $time_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('time', 'Khung giờ này đã tồn tại, vui lòng đổi khung giờ khác!');
            }
        });
    }
}
