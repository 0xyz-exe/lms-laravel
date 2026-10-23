<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['course_id', 'user_id', 'enrolled_at'];
    protected function casts(): array { return ['enrolled_at' => 'datetime']; }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(User::class); }
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Course::class); }
    public function progress(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(LessonProgress::class); }
}
