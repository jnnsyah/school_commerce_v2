<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academic\SchoolClass;

class ClassMajor extends Model
{
    use HasFactory;

    protected $table = 'class_majors';
    
    protected $fillable = [
        'name',
        'short_name',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'major_id');
    }
}