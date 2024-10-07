<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClassroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'classroom_id',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'classroom_id' => 'integer',
    ];

    public function classroom(): HasMany
    {
        return $this->hasMany(Classroom::class, 'id', 'classroom_id');
    }

    public function student(): HasMany
    {
        return $this->hasMany(Student::class, 'id', 'student_id');
    }
}
