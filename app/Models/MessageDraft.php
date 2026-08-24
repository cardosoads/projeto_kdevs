<?php

namespace App\Models;

use App\Enums\MessageDraftStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessageDraft extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => MessageDraftStatus::class]; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function contactPoint(): BelongsTo { return $this->belongsTo(ContactPoint::class); }
    public function approvals(): HasMany { return $this->hasMany(Approval::class); }
    public function interactions(): HasMany { return $this->hasMany(Interaction::class); }
}
