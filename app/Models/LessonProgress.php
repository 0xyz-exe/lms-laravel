<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $fillable = ['enrollment_id', 'lesson_id', 'completed_at'];
    protected function casts(): array { return ['completed_at' => 'datetime']; }
    public function enrollment(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Lesson::class); }
}
