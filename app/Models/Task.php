<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => TaskStatus::class, 'due_at' => 'datetime', 'completed_at' => 'datetime']; }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}
