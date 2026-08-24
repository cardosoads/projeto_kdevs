<?php

namespace App\Models;

use App\Enums\LeadStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => LeadStatus::class]; }
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
    public function contactPoints(): HasMany { return $this->hasMany(ContactPoint::class); }
    public function sourceEvidence(): HasMany { return $this->hasMany(SourceEvidence::class); }
    public function diagnosis(): HasOne { return $this->hasOne(Diagnosis::class); }
    public function offers(): HasMany { return $this->hasMany(Offer::class); }
    public function messageDrafts(): HasMany { return $this->hasMany(MessageDraft::class); }
    public function interactions(): HasMany { return $this->hasMany(Interaction::class); }
    public function followUps(): HasMany { return $this->hasMany(FollowUp::class); }
    public function projects(): HasMany { return $this->hasMany(Project::class); }
}
