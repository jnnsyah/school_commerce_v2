<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes\ClassModel;

class ClassMajor extends Model
{
    use HasFactory;

    protected $table = 'class_majors';
    protected $primaryKey = 'id'; // PAKAI id
    
    public $timestamps = false;

    protected $fillable = ['name', 'short_name'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'major_id', 'id'); // major_id sebagai foreign key
    }
}