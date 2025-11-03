<?php

namespace App\Http\Controllers\Class;

use App\Http\Controllers\Controller;
use App\Models\Classes\ClassModel;
use App\Models\Classes\ClassGrade;
use App\Models\Classes\ClassMajor;
use App\Models\Classes\ClassSection;
use App\Models\Users\User;
use App\Models\Users\UserStudent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(): View
    {
        $classes = ClassModel::with(['grade', 'major', 'section', 'teacher', 'students'])
                            ->latest()
                            ->paginate(10);
        
        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::whereHas('teacherProfile', function($query) {
            $query->where('is_wali_kelas', true);
        })->get();

        return view('classes.create', compact('grades', 'majors', 'sections', 'teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'grade_id' => 'required|exists:class_grades,id',
            'major_id' => 'required|exists:class_majors,id',
            'section_id' => 'required|exists:class_sections,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        // Cek jika kelas sudah ada
        $existingClass = ClassModel::where('grade_id', $request->grade_id)
                                ->where('major_id', $request->major_id)
                                ->where('section_id', $request->section_id)
                                ->first();

        if ($existingClass) {
            return redirect()->back()
                ->with('error', 'Kelas dengan kombinasi tersebut sudah ada!')
                ->withInput();
        }

        ClassModel::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dibuat');
    }

    public function show(ClassModel $class): View
    {
        $class->load(['grade', 'major', 'section', 'teacher', 'students.user']);
        return view('classes.show', compact('class'));
    }

    public function edit(ClassModel $class): View
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::whereHas('teacherProfile', function($query) {
            $query->where('is_wali_kelas', true);
        })->get();

        return view('classes.edit', compact('class', 'grades', 'majors', 'sections', 'teachers'));
    }

    public function update(Request $request, ClassModel $class): RedirectResponse
    {
        $request->validate([
            'grade_id' => 'required|exists:class_grades,id',
            'major_id' => 'required|exists:class_majors,id',
            'section_id' => 'required|exists:class_sections,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(ClassModel $class): RedirectResponse
    {
        // Cek jika kelas masih punya students
        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus');
    }

    /**
     * Manage students in class
     */
    public function manageStudents(ClassModel $class): View
    {
        $students = User::whereHas('studentProfile')
                    ->with('studentProfile')
                    ->get();
        
        $class->load('students.user');

        return view('classes.manage-students', compact('class', 'students'));
    }

    public function updateStudents(Request $request, ClassModel $class): RedirectResponse
    {
        $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Update students class assignment
        UserStudent::whereIn('user_id', $request->student_ids ?? [])
                ->update(['class_id' => $class->id]);

        // Remove students not in the list from this class
        UserStudent::where('class_id', $class->id)
                ->whereNotIn('user_id', $request->student_ids ?? [])
                ->update(['class_id' => null]);

        return redirect()->route('classes.manage-students', $class)
            ->with('success', 'Siswa berhasil diupdate');
    }
}