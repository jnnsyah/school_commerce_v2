<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variantId = $this->route('variant') ? $this->route('variant')->id : null;

        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_at' => 'required|integer|min:0',
            'status_id' => 'required|exists:product_statuses,id',
            'options' => 'required|array|min:1',
            'options.*.name' => 'required|string|max:50',
            'options.*.value' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama variant harus diisi.',
            'price.required' => 'Harga variant harus diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock_at.required' => 'Stok awal harus diisi.',
            'stock_at.min' => 'Stok tidak boleh negatif.',
            'status_id.required' => 'Status variant harus dipilih.',
            'options.required' => 'Opsi variant harus diisi.',
            'options.min' => 'Minimal 1 opsi variant harus diisi.',
            'options.*.name.required' => 'Nama opsi harus diisi.',
            'options.*.value.required' => 'Value opsi harus diisi.',
        ];
    }
}