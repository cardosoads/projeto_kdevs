<?php

namespace App\Models;

use App\Enums\DiagnosisStatus;
use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diagnosis extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $guarded = [];
    protected function casts(): array { return ['status' => DiagnosisStatus::class, 'pain_points' => 'array', 'opportunities' => 'array', 'completed_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function offers(): HasMany { return $this->hasMany(Offer::class); }
}
