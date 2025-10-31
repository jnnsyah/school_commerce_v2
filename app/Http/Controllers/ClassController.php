<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(): View
    {
        $classes = ClassModel::with(['teacher', 'students'])->latest()->paginate(10);
        
        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        $teachers = User::role(['guru_pkwu', 'wali_kelas'])->get();
        return view('classes.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'major' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        ClassModel::create([
            'name' => $request->name,
            'grade' => $request->grade,
            'major' => $request->major,
            'teacher_id' => $request->teacher_id,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dibuat');
    }

    public function show(ClassModel $class): View
    {
        $class->load(['teacher', 'students']);
        return view('classes.show', compact('class'));
    }

    public function edit(ClassModel $class): View
    {
        $teachers = User::role(['guru_pkwu', 'wali_kelas'])->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassModel $class): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'major' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $class->update([
            'name' => $request->name,
            'grade' => $request->grade,
            'major' => $request->major,
            'teacher_id' => $request->teacher_id,
            'location' => $request->location,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

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
        $students = User::role('student')
            ->whereNull('class_id')
            ->orWhere('class_id', $class->id)
            ->get();
            
        $class->load('students');
        
        return view('classes.manage-students', compact('class', 'students'));
    }

    public function updateStudents(Request $request, ClassModel $class): RedirectResponse
    {
        $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Remove all students from this class first
        User::where('class_id', $class->id)->update(['class_id' => null]);

        // Assign selected students to this class
        if ($request->student_ids) {
            User::whereIn('id', $request->student_ids)->update(['class_id' => $class->id]);
        }

        return redirect()->route('classes.manage-students', $class)
            ->with('success', 'Siswa berhasil diupdate');
    }
}