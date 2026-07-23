<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonExample extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'source_code',
        'expected_output',
        'explanation',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
