<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'score', 'answers', 'submitted_at'];
    protected function casts(): array { return ['answers' => 'array', 'submitted_at' => 'datetime']; }
    public function quiz(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Quiz::class); }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(User::class); }
}
