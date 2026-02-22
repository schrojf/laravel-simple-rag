<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    /** @use HasFactory<\Database\Factories\EntryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'type_id', 'title', 'content', 'meta'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function getTokenEstimateAttribute(): int
    {
        return (int) ceil(strlen($this->content) / 4);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EntryType::class, 'type_id');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'entry_topic');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
