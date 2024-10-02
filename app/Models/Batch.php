<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    public function students()
    {
        return $this->hasMany(User::class);
    }
}
