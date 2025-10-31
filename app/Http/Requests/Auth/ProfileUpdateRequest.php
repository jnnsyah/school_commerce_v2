<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // User bisa update profile sendiri
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'no_hp' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s()]+$/'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date', 'before:-10 years'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.required' => 'Nomor handphone wajib diisi',
            'no_hp.regex' => 'Format nomor handphone tidak valid',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran foto maksimal 2MB',
        ];
    }
}