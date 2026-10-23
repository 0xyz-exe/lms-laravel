<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['quiz_id', 'prompt', 'options', 'correct_option'];
    protected function casts(): array { return ['options' => 'array']; }
    public function quiz(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Quiz::class); }
}
