<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'content', 'media_url', 'position'];
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Course::class); }
}
