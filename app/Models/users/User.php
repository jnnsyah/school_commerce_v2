<?php

namespace App\Models\Users;

use App\Models\Classes\ClassModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'no_hp',
        'gender',
        'birth_date',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token', // Kita keep remember_token untuk functionality Breeze
    ];

    protected $guard_name = 'web';

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Find user by username or email
     */
    public function findForLogin($identifier)
    {
        return $this->where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();
    }

    /**
     * User class relation
     */

    // Untuk teacher - one-to-one dengan user_teachers
    public function teacherProfile()
    {
        return $this->hasOne(UserTeacher::class, 'user_id');
    }

    // Untuk student - one-to-one dengan user_students
    public function studentProfile()
    {
        return $this->hasOne(UserStudent::class, 'user_id');
    }

    // Untuk wali kelas - one-to-many dengan classes
    public function teachingClasses()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    /**
     * Helper methods
     */
    public function isTeacher()
    {
        return $this->teacherProfile !== null;
    }

    public function isStudent()
    {
        return $this->studentProfile !== null;
    }

    public function isPkwuTeacher()
    {
        return $this->isTeacher() && $this->teacherProfile->is_pkwu;
    }

    public function isWaliKelas()
    {
        return $this->isTeacher() && $this->teacherProfile->is_wali_kelas;
    }

    public function getCurrentClassAttribute()
    {
        if ($this->isStudent()) {
            return $this->studentProfile->class;
        }
        
        return null;
    }

    public function getNisnAttribute()
    {
        return $this->isStudent() ? $this->studentProfile->nisn : null;
    }

    public function getNipAttribute()
    {
        return $this->isTeacher() ? $this->teacherProfile->nip : null;
    }
}