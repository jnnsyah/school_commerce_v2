<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Academic\SchoolClass;
use App\Models\User\UserStudent;
use App\Models\User\UserTeacher;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username', 
        'email',
        'password',
        'no_hp',
        'gender',
        'photo',
        'birth_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    // Relationships
    public function student()
    {
        return $this->hasOne(UserStudent::class, 'user_id');
    }

    public function teacher()
    {
        return $this->hasOne(UserTeacher::class, 'user_id');
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    // Helper Methods
    public function isStudent()
    {
        return $this->hasRole('student');
    }

    public function isTeacher()
    {
        return $this->hasAnyRole(['guru_pkwu', 'wali_kelas', 'guru_biasa']);
    }

    public function isWaliKelas()
    {
        return $this->hasRole('wali_kelas');
    }

    public function isGuruPkwu()
    {
        return $this->hasRole('guru_pkwu');
    }

    public function getRoleName()
    {
        return $this->roles->first()->name ?? 'No Role';
    }
}