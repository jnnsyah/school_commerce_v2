<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_notes' => 'nullable|string|max:500',
            'agree_terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_notes.max' => 'Catatan pelanggan maksimal 500 karakter.',
            'agree_terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ];
    }
}