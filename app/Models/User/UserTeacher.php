<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class UserTeacher extends Model
{
    use HasFactory;

    protected $table = 'user_teachers';
    
    protected $fillable = [
        'user_id',
        'nip',
        'is_pkwu',
        'is_wali_kelas',
    ];

    protected $casts = [
        'is_pkwu' => 'boolean',
        'is_wali_kelas' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function getRoleType()
    {
        if ($this->is_pkwu) return 'Guru PKWU';
        if ($this->is_wali_kelas) return 'Wali Kelas';
        return 'Guru Biasa';
    }
}