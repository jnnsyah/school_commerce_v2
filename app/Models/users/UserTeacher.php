<?php

namespace App\Models\Users;

use App\Models\Classes\ClassModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'is_pkwu',
        'is_wali_kelas'
    ];

    protected $casts = [
        'is_pkwu' => 'boolean',
        'is_wali_kelas' => 'boolean'
    ];

    public $timestamps = false; 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teachingClasses()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id', 'user_id');
    }
}