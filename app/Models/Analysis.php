<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Analysis extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'word_count' => 'integer',
        'sentence_count' => 'integer',
        'avg_sentence_length' => 'float',
        'keyword_density' => 'float',
        'readability_score' => 'float',
        'content_health_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }
}
