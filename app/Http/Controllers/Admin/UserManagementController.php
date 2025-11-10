<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserStudent;
use App\Models\User\UserTeacher;
use App\Models\Academic\SchoolClass;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.view')->only('index', 'show');
        $this->middleware('permission:user.create')->only('create', 'store');
        $this->middleware('permission:user.edit')->only('edit', 'update');
        $this->middleware('permission:user.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = User::with(['roles', 'student.class', 'teacher']);

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::where('name', '!=', 'super_admin')->get();
        $classes = SchoolClass::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'roles', 'classes'));
    }

    public function students(Request $request)
    {
        $query = User::role('student')->with(['student.class']);

        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $students = $query->latest()->paginate(20);
        $classes = SchoolClass::where('is_active', true)->get();

        return view('admin.users.students', compact('students', 'classes'));
    }

    public function teachers(Request $request)
    {
        $teachers = User::role(['guru_pkwu', 'wali_kelas', 'guru_biasa'])
            ->with(['teacher', 'classes'])
            ->latest()
            ->paginate(20);

        return view('admin.users.teachers', compact('teachers'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super_admin')->get();
        $classes = SchoolClass::where('is_active', true)->get();
        
        return view('admin.users.create', compact('roles', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string',
            'no_hp' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:L,P',
            'birth_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Create student record if role is student
        if ($request->role === 'student' && $request->class_id) {
            UserStudent::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'class_id' => $request->class_id,
                'is_admin_class' => $request->is_admin_class ?? false,
            ]);
        }

        // Create teacher record if role is teacher
        if (in_array($request->role, ['guru_pkwu', 'wali_kelas', 'guru_biasa'])) {
            UserTeacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'is_pkwu' => $request->role === 'guru_pkwu',
                'is_wali_kelas' => $request->role === 'wali_kelas',
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'super_admin')->get();
        $classes = SchoolClass::where('is_active', true)->get();
        
        return view('admin.users.edit', compact('user', 'roles', 'classes'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|string',
            'no_hp' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:L,P',
            'birth_date' => 'nullable|date',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'no_hp' => $request->no_hp,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        // Update role
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}