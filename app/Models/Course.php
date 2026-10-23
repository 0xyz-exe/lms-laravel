<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['instructor_id', 'title', 'slug', 'description', 'category', 'published'];

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(User::class, 'instructor_id'); }
    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Lesson::class)->orderBy('position'); }
    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Enrollment::class); }
    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Quiz::class); }
}
