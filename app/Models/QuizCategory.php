<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'programming_language_id',
        'title',
        'slug',
        'description',
        'difficulty',
        'status',
        'order_number',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function programmingLanguage(): BelongsTo
    {
        return $this->belongsTo(ProgrammingLanguage::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('order_number');
    }
}
