<?php

namespace App\Models;

use App\Enums\FollowUpStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => FollowUpStatus::class, 'scheduled_at' => 'datetime', 'completed_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function interaction(): BelongsTo { return $this->belongsTo(Interaction::class); }
}
