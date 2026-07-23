<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleChangeLog extends Model
{
    protected $fillable = [
        'changed_by',
        'user_id',
        'previous_role',
        'new_role',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
