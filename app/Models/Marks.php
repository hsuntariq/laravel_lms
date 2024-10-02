<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    use HasFactory;

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answers::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
