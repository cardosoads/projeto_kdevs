<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LogicException;

class Activity extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Activities are append-only and cannot be updated.'));
        static::deleting(fn () => throw new LogicException('Activities are append-only and cannot be deleted.'));
    }

    protected function casts(): array { return ['properties' => 'array', 'occurred_at' => 'immutable_datetime', 'created_at' => 'immutable_datetime']; }
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_user_id'); }
    public function subject(): MorphTo { return $this->morphTo(); }
}
