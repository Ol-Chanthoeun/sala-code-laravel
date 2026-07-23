<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'slug',
        'short_description',
        'lesson_content',
        'source_code',
        'expected_output',
        'explanation',
        'code_explanation',
        'common_mistakes',
        'tips',
        'summary',
        'exercise',
        'quiz',
        'difficulty_level',
        'estimated_learning_time',
        'video_url',
        'order_number',
        'status',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quiz' => 'array',
            'estimated_learning_time' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examples(): HasMany
    {
        return $this->hasMany(LessonExample::class);
    }
}
