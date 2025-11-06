<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classId = $this->route('class') ? $this->route('class')->class_id : null;

        return [
            'grade_id' => 'required|exists:class_grades,id',
            'major_id' => 'required|exists:class_majors,id',
            'section_id' => 'required|exists:class_sections,id',
            'teacher_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($classId) {
                    // Check if teacher is already assigned to another active class
                    $existingClass = \App\Models\Academic\SchoolClass::where('teacher_id', $value)
                        ->where('is_active', true)
                        ->when($classId, function ($query) use ($classId) {
                            return $query->where('class_id', '!=', $classId);
                        })
                        ->first();

                    if ($existingClass) {
                        $fail('Guru ini sudah menjadi wali kelas di kelas lain.');
                    }
                }
            ],
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_id.required' => 'Tingkatan kelas harus dipilih.',
            'grade_id.exists' => 'Tingkatan kelas tidak valid.',
            'major_id.required' => 'Jurusan harus dipilih.',
            'major_id.exists' => 'Jurusan tidak valid.',
            'section_id.required' => 'Section harus dipilih.',
            'section_id.exists' => 'Section tidak valid.',
            'teacher_id.required' => 'Wali kelas harus dipilih.',
            'teacher_id.exists' => 'Wali kelas tidak valid.',
        ];
    }
}