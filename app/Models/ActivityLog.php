<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'role',
        'action',
        'module',
        'target_type',
        'target_id',
        'target_name',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'severity',
        'method',
        'route_name',
        'context',
    ];

    protected function casts(): array
    {
        return ['context' => 'array', 'old_values' => 'array', 'new_values' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActorEmailAttribute(): ?string
    {
        return $this->user_email ?: $this->user?->email;
    }

    public function getTargetLabelAttribute(): ?string
    {
        return $this->target_name ?: ($this->context['target_email'] ?? $this->context['target_name'] ?? null);
    }
}
