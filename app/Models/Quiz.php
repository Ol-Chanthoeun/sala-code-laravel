<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'programming_language_id',
        'quiz_category_id',
        'title',
        'slug',
        'description',
        'difficulty',
        'estimated_time',
        'passing_score',
        'status',
        'order_number',
    ];

    protected function casts(): array
    {
        return [
            'estimated_time' => 'integer',
            'passing_score' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function programmingLanguage(): BelongsTo
    {
        return $this->belongsTo(ProgrammingLanguage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(QuizCategory::class, 'quiz_category_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order_number');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
