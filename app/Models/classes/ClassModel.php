<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Users\UserStudent;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id'; 
    
    public $timestamps = true;

    protected $fillable = [
        'grade_id',
        'major_id', 
        'section_id',
        'teacher_id'
    ];

    /**
     * RELATIONSHIPS
     */
    public function grade()
    {
        return $this->belongsTo(ClassGrade::class, 'grade_id', 'id');
    }

    public function major()
    {
        return $this->belongsTo(ClassMajor::class, 'major_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(UserStudent::class, 'class_id', 'id');
    }

    /**
     * ACCESSORS
     */
    public function getFullNameAttribute()
    {
        if ($this->grade && $this->major && $this->section) {
            return $this->grade->name . ' ' . $this->major->short_name . ' ' . $this->section->name;
        }
        return 'Class Not Found';
    }

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }
}