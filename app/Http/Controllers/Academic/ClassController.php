<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\ClassRequest;
use App\Models\Academic\SchoolClass;
use App\Models\Academic\ClassGrade;
use App\Models\Academic\ClassMajor;
use App\Models\Academic\ClassSection;
use App\Models\User\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:class.view')->only('index', 'show');
        $this->middleware('permission:class.create')->only('create', 'store');
        $this->middleware('permission:class.edit')->only('edit', 'update');
        $this->middleware('permission:class.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = SchoolClass::with(['grade', 'major', 'section', 'teacher', 'students']);

        // Filter by grade
        if ($request->has('grade_id') && $request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        // Filter by major
        if ($request->has('major_id') && $request->major_id) {
            $query->where('major_id', $request->major_id);
        }

        $classes = $query->latest()->paginate(15);
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();

        return view('academic.classes.index', compact('classes', 'grades', 'majors'));
    }

    public function create()
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::role('wali_kelas')->whereDoesntHave('classes')->get();

        return view('academic.classes.create', compact('grades', 'majors', 'sections', 'teachers'));
    }

    public function store(ClassRequest $request)
    {
        $class = SchoolClass::create([
            'grade_id' => $request->grade_id,
            'major_id' => $request->major_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class)
    {
        $class->load(['grade', 'major', 'section', 'teacher', 'students.user', 'products' => function($query) {
            $query->with('category', 'status')->latest();
        }]);

        return view('academic.classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();
        $teachers = User::role('wali_kelas')
            ->where(function($query) use ($class) {
                $query->whereDoesntHave('classes')
                      ->orWhereHas('classes', function($q) use ($class) {
                          $q->where('class_id', $class->class_id);
                      });
            })
            ->get();

        return view('academic.classes.edit', compact('class', 'grades', 'majors', 'sections', 'teachers'));
    }

    public function update(ClassRequest $request, SchoolClass $class)
    {
        $class->update([
            'grade_id' => $request->grade_id,
            'major_id' => $request->major_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        // Check if class has students or products
        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class that has students.');
        }

        if ($class->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class that has products.');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function manageStudents(SchoolClass $class)
    {
        $class->load('students.user');
        $availableStudents = User::role('student')
            ->whereDoesntHave('student')
            ->get();

        return view('academic.students.assign', compact('class', 'availableStudents'));
    }

    public function assignStudent(Request $request, SchoolClass $class)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if user is already in a class
        if ($user->student) {
            return redirect()->back()
                ->with('error', 'Student is already assigned to a class.');
        }

        // Create student record
        $user->student()->create([
            'nisn' => $request->nisn ?? 'NISN-' . $user->id,
            'class_id' => $class->class_id,
            'is_admin_class' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Student assigned to class successfully.');
    }
}