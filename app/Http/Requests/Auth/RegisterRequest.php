<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'no_hp' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s()]+$/'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date', 'before:-10 years'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.max' => 'Username maksimal 255 karakter',
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_hp.required' => 'Nomor handphone wajib diisi',
            'no_hp.regex' => 'Format nomor handphone tidak valid',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'birth_date.date' => 'Format tanggal lahir tidak valid',
            'birth_date.before' => 'Minimal usia 10 tahun',
        ];
    }
}
