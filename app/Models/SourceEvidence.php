<?php

namespace App\Models;

use App\Models\Concerns\HasAttachmentsAndActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SourceEvidence extends Model
{
    use HasAttachmentsAndActivities, HasFactory;
    protected $table = 'source_evidence';
    protected $guarded = [];
    protected function casts(): array { return ['metadata' => 'array', 'captured_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
}
