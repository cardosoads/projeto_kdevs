<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasAttachmentsAndActivities, HasFactory;

    protected $guarded = [];

    protected function casts(): array { return ['status' => CampaignStatus::class, 'starts_at' => 'datetime', 'ends_at' => 'datetime']; }
    public function leads(): HasMany { return $this->hasMany(Lead::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
