<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'role' => 'required|string|in:super_admin,admin,guru_pkwu,wali_kelas,guru_biasa,student',
        ];

        // Student specific rules
        if ($this->role === 'student') {
            $rules['nisn'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('user_students', 'nisn')->ignore($userId, 'user_id'),
            ];
            $rules['class_id'] = 'required|exists:classes,class_id';
            $rules['is_admin_class'] = 'boolean';
        }

        // Teacher specific rules
        if (in_array($this->role, ['guru_pkwu', 'wali_kelas', 'guru_biasa'])) {
            $rules['nip'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('user_teachers', 'nip')->ignore($userId, 'user_id'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'role.required' => 'Role harus dipilih.',
            'nisn.required' => 'NISN harus diisi.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'class_id.required' => 'Kelas harus dipilih.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
        ];
    }
}