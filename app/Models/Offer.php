<?php

namespace App\Models;

use App\Enums\OfferStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => OfferStatus::class, 'price' => 'decimal:2']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function diagnosis(): BelongsTo { return $this->belongsTo(Diagnosis::class); }
    public function messageDrafts(): HasMany { return $this->hasMany(MessageDraft::class); }
    public function projects(): HasMany { return $this->hasMany(Project::class); }
}
