<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_assigned');
    }
}
