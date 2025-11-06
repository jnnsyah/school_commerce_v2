<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academic\SchoolClass;

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';
    
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'section_id');
    }
}