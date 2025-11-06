<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->product_id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // For create, require images
        if (!$productId) {
            $rules['images'] = 'required|array|min:1';
            $rules['images.*'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'description.max' => 'Deskripsi produk maksimal 1000 karakter.',
            'price.required' => 'Harga produk harus diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'category_id.required' => 'Kategori produk harus dipilih.',
            'category_id.exists' => 'Kategori produk tidak valid.',
            'images.required' => 'Gambar produk harus diupload.',
            'images.min' => 'Minimal 1 gambar produk harus diupload.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'images.*' => 'gambar produk',
        ];
    }
}