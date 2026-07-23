<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'explanation',
        'difficulty',
        'points',
        'order_number',
        'correct_choice_id',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuizChoice::class)->orderBy('order_number');
    }

    public function correctChoice(): BelongsTo
    {
        return $this->belongsTo(QuizChoice::class, 'correct_choice_id');
    }
}
