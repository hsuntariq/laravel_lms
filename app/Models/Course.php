<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'level',
        'language',
        'visibility',
        'short_description',
        'price',
        'thumbnail',
        'description',
        'featured',
        'learning',
        'requirements'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
}
