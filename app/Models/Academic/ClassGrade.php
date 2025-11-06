<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academic\SchoolClass;

class ClassGrade extends Model
{
    use HasFactory;

    protected $table = 'class_grades';
    
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'grade_id');
    }
}