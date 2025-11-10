<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academic\SchoolClass;
use App\Models\Academic\ClassGrade;
use App\Models\Academic\ClassMajor;
use App\Models\Academic\ClassSection;
use App\Models\User\User;
use Illuminate\Http\Request;

class ClassManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:class.view')->only('index', 'show');
        $this->middleware('permission:class.create')->only('create', 'store');
        $this->middleware('permission:class.edit')->only('edit', 'update', 'assignTeacher');
        $this->middleware('permission:class.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = SchoolClass::with(['grade', 'major', 'section', 'teacher']);

        // Filter by grade
        if ($request->has('grade_id') && $request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        // Filter by major
        if ($request->has('major_id') && $request->major_id) {
            $query->where('major_id', $request->major_id);
        }

        $classes = $query->latest()->paginate(20);
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::role('wali_kelas')->get();

        return view('admin.classes.index', compact('classes', 'grades', 'majors', 'sections', 'teachers'));
    }

    public function create()
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::role('wali_kelas')->get();

        return view('admin.classes.create', compact('grades', 'majors', 'sections', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:class_grades,id',
            'major_id' => 'required|exists:class_majors,id',
            'section_id' => 'required|exists:class_sections,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        // Check if class already exists
        $existingClass = SchoolClass::where('grade_id', $request->grade_id)
            ->where('major_id', $request->major_id)
            ->where('section_id', $request->section_id)
            ->first();

        if ($existingClass) {
            return redirect()->back()->with('error', 'Kelas sudah ada');
        }

        SchoolClass::create([
            'grade_id' => $request->grade_id,
            'major_id' => $request->major_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dibuat');
    }

    public function edit(SchoolClass $class)
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::role('wali_kelas')->get();

        return view('admin.classes.edit', compact('class', 'grades', 'majors', 'sections', 'teachers'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'grade_id' => 'required|exists:class_grades,id',
            'major_id' => 'required|exists:class_majors,id',
            'section_id' => 'required|exists:class_sections,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update([
            'grade_id' => $request->grade_id,
            'major_id' => $request->major_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function assignTeacher(Request $request, SchoolClass $class)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        // Check if teacher is a wali_kelas
        $teacher = User::find($request->teacher_id);
        if (!$teacher->hasRole('wali_kelas')) {
            return redirect()->back()->with('error', 'Guru harus memiliki role wali_kelas');
        }

        $class->update(['teacher_id' => $request->teacher_id]);

        return redirect()->back()->with('success', 'Wali kelas berhasil ditugaskan');
    }

    public function destroy(SchoolClass $class)
    {
        // Check if class has products or students
        if ($class->products()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas yang memiliki produk');
        }

        if ($class->students()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus');
    }
}