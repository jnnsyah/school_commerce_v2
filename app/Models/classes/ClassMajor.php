<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassMajor extends Model
{
    use HasFactory;

    protected $primaryKey = 'major_id';
    
    protected $fillable = ['name', 'short_name'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'major_id');
    }
}