<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_description',
        'description',
        'image',
        'thumbnail',
        'programming_language',
        'difficulty_level',
        'status',
        'created_by',
        'price',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('order_number');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order_number');
    }
}
