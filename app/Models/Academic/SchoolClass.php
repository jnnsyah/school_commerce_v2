<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\User\UserStudent;
use App\Models\Product\Product;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    
    protected $fillable = [
        'grade_id',
        'major_id', 
        'section_id',
        'teacher_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
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

    public function products()
    {
        return $this->hasMany(Product::class, 'class_id');
    }

    // Helper Methods
    public function getFullName()
    {
        return "{$this->grade->name} {$this->major->short_name} {$this->section->name}";
    }

    public function getStudentCount()
    {
        return $this->students()->count();
    }

    public function getActiveProductsCount()
    {
        return $this->products()->whereHas('status', function($query) {
            $query->where('name', 'approved');
        })->count();
    }

    public function isActive()
    {
        return $this->is_active;
    }
}