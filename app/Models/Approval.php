<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => ApprovalStatus::class, 'decided_at' => 'datetime']; }
    public function messageDraft(): BelongsTo { return $this->belongsTo(MessageDraft::class); }
    public function decider(): BelongsTo { return $this->belongsTo(User::class, 'decided_by'); }
}
