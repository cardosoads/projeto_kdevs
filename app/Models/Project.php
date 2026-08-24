<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => ProjectStatus::class, 'starts_on' => 'date', 'due_on' => 'date', 'completed_on' => 'date']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function milestones(): HasMany { return $this->hasMany(Milestone::class); }
    public function tasks(): HasMany { return $this->hasMany(Task::class); }
}
