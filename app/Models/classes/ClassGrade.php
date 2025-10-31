<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGrade extends Model
{
    use HasFactory;

    protected $primaryKey = 'grade_id';
    
    protected $fillable = ['name'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'grade_id');
    }
}