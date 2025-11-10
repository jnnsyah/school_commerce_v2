<?php
// app/Http/Requests/Inventory/StockAdjustmentRequest.php - BUAT BARU

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1',
            'adjustment_type' => 'required|in:add,subtract',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'Jumlah penyesuaian harus diisi',
            'quantity.integer' => 'Jumlah harus berupa angka',
            'quantity.min' => 'Jumlah minimal 1',
            'adjustment_type.required' => 'Jenis penyesuaian harus dipilih',
            'adjustment_type.in' => 'Jenis penyesuaian tidak valid',
        ];
    }
}