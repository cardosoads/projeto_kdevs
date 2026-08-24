<?php

namespace App\Models;

use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactPoint extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['is_primary' => 'boolean', 'verified_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function messageDrafts(): HasMany { return $this->hasMany(MessageDraft::class); }
    public function interactions(): HasMany { return $this->hasMany(Interaction::class); }
}
