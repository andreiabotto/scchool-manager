<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'type' => 'integer'
    ];

    public function student(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
