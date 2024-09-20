<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
