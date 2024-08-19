<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'title',
        'description',
        'instructor',
        'duration'
    ];


    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id', 'course_id');
    }
}
