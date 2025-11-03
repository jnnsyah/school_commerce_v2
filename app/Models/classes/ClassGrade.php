<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes\ClassModel;

class ClassGrade extends Model
{
    use HasFactory;

    protected $table = 'class_grades';
    protected $primaryKey = 'id'; // PAKAI id
    
    public $timestamps = false;

    protected $fillable = ['name'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'grade_id', 'id'); // grade_id sebagai foreign key
    }
}