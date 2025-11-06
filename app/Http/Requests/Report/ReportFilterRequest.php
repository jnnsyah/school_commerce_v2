<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|exists:order_statuses,status_id',
            'category_id' => 'nullable|exists:product_categories,id',
            'class_id' => 'nullable|exists:classes,class_id',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal akhir.',
            'end_date.required' => 'Tanggal akhir harus diisi.',
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
            'status.exists' => 'Status order tidak valid.',
            'category_id.exists' => 'Kategori produk tidak valid.',
            'class_id.exists' => 'Kelas tidak valid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Set default date range if not provided
        if (!$this->has('start_date')) {
            $this->merge([
                'start_date' => now()->subMonth()->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]);
        }
    }
}