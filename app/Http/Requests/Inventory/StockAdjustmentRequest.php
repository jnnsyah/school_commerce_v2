<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1|max:1000',
            'note' => 'required|string|max:500',
            'variant_id' => 'nullable|required_without_all:extra_id|exists:product_variants,id',
            'extra_id' => 'nullable|required_without_all:variant_id|exists:product_extras,id',
        ];
    }

    public function messages(): array
    {
        return [
            'adjustment_type.required' => 'Jenis penyesuaian harus dipilih.',
            'adjustment_type.in' => 'Jenis penyesuaian tidak valid.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 1000.',
            'note.required' => 'Keterangan harus diisi.',
            'note.max' => 'Keterangan maksimal 500 karakter.',
            'variant_id.exists' => 'Variant tidak valid.',
            'extra_id.exists' => 'Extra tidak valid.',
            'variant_id.required_without_all' => 'Pilih variant atau extra untuk penyesuaian stok.',
            'extra_id.required_without_all' => 'Pilih variant atau extra untuk penyesuaian stok.',
        ];
    }
}