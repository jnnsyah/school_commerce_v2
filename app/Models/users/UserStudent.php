<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Classes\ClassModel;

class UserStudent extends Model
{
    use HasFactory;

    protected $table = 'user_students';
    protected $primaryKey = 'user_id';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nisn',
        'class_id',
        'is_admin_class'
    ];

    protected $casts = [
        'is_admin_class' => 'boolean'
        // JANGAN cast nisn ke integer
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'class_id');
    }
}