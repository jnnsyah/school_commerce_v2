<?php

namespace App\Http\Requests;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            // Basic Information
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique(User::class)->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            
            // Contact Information
            'no_hp' => [
                'required',
                'string',
                'max:20',
                'regex:/^08[0-9]{9,}$/',
                Rule::unique(User::class)->ignore($userId),
            ],
            
            // Personal Information
            'gender' => ['required', 'string', 'in:L,P'],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            
            // Profile Photo
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            
            // Password (optional update)
            'current_password' => ['sometimes', 'required_with:password', 'current_password'],
            'password' => [
                'sometimes',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'no_hp' => 'nomor telepon',
            'birth_date' => 'tanggal lahir',
            'current_password' => 'password saat ini',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'no_hp.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'birth_date.after' => 'Tanggal lahir harus setelah tahun 1900',
            'current_password.current_password' => 'Password saat ini tidak sesuai',
            'photo.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number format
        if ($this->has('no_hp')) {
            $this->merge([
                'no_hp' => preg_replace('/[^0-9]/', '', $this->no_hp),
            ]);
        }

        // Ensure username is lowercase
        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower($this->username),
            ]);
        }
    }
}