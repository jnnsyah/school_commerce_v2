<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Models\User\User;
use App\Models\User\UserStudent;
use App\Models\User\UserTeacher;
use App\Models\Academic\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
            $query->role($request->role);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            if ($request->type === 'student') {
                $query->has('student');
            } elseif ($request->type === 'teacher') {
                $query->has('teacher');
            }
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $classes = SchoolClass::where('is_active', true)->get();
        
        return view('users.create', compact('roles', 'classes'));
    }

    public function store(UserRequest $request)
    {
        // Create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Create student or teacher record
        if ($request->role === 'student') {
            UserStudent::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'class_id' => $request->class_id,
                'is_admin_class' => $request->has('is_admin_class'),
            ]);
        } elseif (in_array($request->role, ['guru_pkwu', 'wali_kelas', 'guru_biasa'])) {
            UserTeacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'is_pkwu' => $request->role === 'guru_pkwu',
                'is_wali_kelas' => $request->role === 'wali_kelas',
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'student.class', 'teacher', 'classes']);
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $classes = SchoolClass::where('is_active', true)->get();
        $user->load(['student', 'teacher']);

        return view('users.edit', compact('user', 'roles', 'classes'));
    }

    public function update(UserRequest $request, User $user)
    {
        // Update user basic info
        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        // Update password if provided
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Sync roles
        $user->syncRoles([$request->role]);

        // Update student or teacher record
        if ($request->role === 'student') {
            if ($user->student) {
                $user->student->update([
                    'nisn' => $request->nisn,
                    'class_id' => $request->class_id,
                    'is_admin_class' => $request->has('is_admin_class'),
                ]);
            } else {
                UserStudent::create([
                    'user_id' => $user->id,
                    'nisn' => $request->nisn,
                    'class_id' => $request->class_id,
                    'is_admin_class' => $request->has('is_admin_class'),
                ]);
            }

            // Remove teacher record if exists
            if ($user->teacher) {
                $user->teacher->delete();
            }
        } elseif (in_array($request->role, ['guru_pkwu', 'wali_kelas', 'guru_biasa'])) {
            if ($user->teacher) {
                $user->teacher->update([
                    'nip' => $request->nip,
                    'is_pkwu' => $request->role === 'guru_pkwu',
                    'is_wali_kelas' => $request->role === 'wali_kelas',
                ]);
            } else {
                UserTeacher::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'is_pkwu' => $request->role === 'guru_pkwu',
                    'is_wali_kelas' => $request->role === 'wali_kelas',
                ]);
            }

            // Remove student record if exists
            if ($user->student) {
                $user->student->delete();
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}