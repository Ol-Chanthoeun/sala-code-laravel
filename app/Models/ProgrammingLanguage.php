<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgrammingLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'difficulty',
        'estimated_time',
        'status',
        'order_number',
    ];

    protected function casts(): array
    {
        return [
            'estimated_time' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): HasMany
    {
        return $this->hasMany(QuizCategory::class)->orderBy('order_number');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('order_number');
    }
}
