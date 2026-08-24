<?php

namespace App\Models;

use App\Enums\MilestoneStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => MilestoneStatus::class, 'due_on' => 'date', 'completed_at' => 'datetime']; }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function tasks(): HasMany { return $this->hasMany(Task::class); }
}
