<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- KHÔNG TRÙNG TÊN SP TRONG CÙNG LOẠI (BỎ QUA CHÍNH NÓ) ---
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')
                    ->where('product_type_id', $this->product_type_id)
                    ->ignore($this->product_id, 'product_id'),
            ],

            'product_type_id' => 'required|exists:product_types,product_type_id',
            'product_size_id' => 'nullable',

            'price' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',

            'description' => 'required|array',
            'description.*' => 'required|string',

            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'deleted_images.*' => 'nullable|integer',
            'deleted_records.*' => 'nullable|integer',

            // --- SIZE ---
            'sizes' => 'nullable|array',
            'sizes.*.name' => 'required_with:sizes|string|distinct',
            'sizes.*.price' => 'nullable|string',
            'sizes.*.quantity' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại trong loại sản phẩm này.',

            'product_type_id.required' => 'Vui lòng chọn loại sản phẩm.',
            'product_type_id.exists' => 'Loại sản phẩm không hợp lệ.',

            'price.string' => 'Giá tiền không hợp lệ.',
            'quantity.integer' => 'Số lượng phải là số.',
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',

            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.*.required' => 'Mô tả không được để trống.',

            'image.*.image' => 'File tải lên phải là hình ảnh.',
            'image.*.mimes' => 'Hình ảnh phải là JPG, PNG hoặc WEBP.',
            'image.*.max' => 'Kích thước hình ảnh không được vượt quá 4MB.',

            // --- SIZE ---
            'sizes.*.name.required_with' => 'Vui lòng chọn tên size.',
            'sizes.*.name.distinct' => 'Không được chọn trùng size trong cùng sản phẩm.',
            'sizes.*.quantity.integer' => 'Số lượng size phải là số.',
            'sizes.*.quantity.min' => 'Số lượng size không được nhỏ hơn 0.',
        ];
    }
}
