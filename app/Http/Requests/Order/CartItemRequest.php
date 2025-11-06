<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,product_id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100',
            'extras' => 'sometimes|array',
            'extras.*.id' => 'required_with:extras|exists:product_extras,id',
            'extras.*.quantity' => 'required_with:extras|integer|min:1|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk harus dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'variant_id.exists' => 'Variant produk tidak valid.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 100.',
            'extras.*.id.required' => 'ID extra harus diisi.',
            'extras.*.id.exists' => 'Extra tidak valid.',
            'extras.*.quantity.required' => 'Jumlah extra harus diisi.',
            'extras.*.quantity.min' => 'Jumlah extra minimal 1.',
            'extras.*.quantity.max' => 'Jumlah extra maksimal 10.',
        ];
    }
}