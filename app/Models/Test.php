<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'course_name',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
    ];
}