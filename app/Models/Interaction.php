<?php

namespace App\Models;

use App\Enums\InteractionDirection;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interaction extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['direction' => InteractionDirection::class, 'metadata' => 'array', 'occurred_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function contactPoint(): BelongsTo { return $this->belongsTo(ContactPoint::class); }
    public function messageDraft(): BelongsTo { return $this->belongsTo(MessageDraft::class); }
    public function followUps(): HasMany { return $this->hasMany(FollowUp::class); }
}
