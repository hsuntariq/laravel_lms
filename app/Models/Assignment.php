<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public function answer()
    {
        return $this->hasMany(Answers::class);
    }

    public function marks()
    {
        return $this->hasMany(Marks::class);
    }
}
