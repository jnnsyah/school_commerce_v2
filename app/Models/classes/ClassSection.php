<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes\ClassModel;

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';
    protected $primaryKey = 'id'; // PAKAI id
    
    public $timestamps = false;

    protected $fillable = ['name'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'section_id', 'id'); // section_id sebagai foreign key
    }
}