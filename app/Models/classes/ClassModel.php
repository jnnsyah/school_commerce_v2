<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'class_id';
    protected $table = 'classes';

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
        return $this->belongsTo(ClassGrade::class, 'grade_id');
    }

    public function major()
    {
        return $this->belongsTo(ClassMajor::class, 'major_id');
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(UserStudent::class, 'class_id');
    }

    /**
     * ACCESSORS
     */
    public function getFullNameAttribute()
    {
        return "{$this->grade->name} {$this->major->short_name} {$this->section->name}";
    }

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }
}