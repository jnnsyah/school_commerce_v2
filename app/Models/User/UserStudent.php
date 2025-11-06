<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Academic\SchoolClass;

class UserStudent extends Model
{
    use HasFactory;

    protected $table = 'user_students';
    
    protected $fillable = [
        'user_id',
        'nisn',
        'class_id', 
        'is_admin_class',
    ];

    protected $casts = [
        'is_admin_class' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Helper Methods
    public function getClassName()
    {
        return $this->class ? $this->class->getFullName() : 'No Class';
    }
}