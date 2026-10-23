<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['course_id', 'title', 'passing_score'];
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Course::class); }
    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Question::class); }
    public function attempts(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(QuizAttempt::class); }
}
