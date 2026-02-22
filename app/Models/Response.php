<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Response extends Model
{
    /** @use HasFactory<\Database\Factories\ResponseFactory> */
    use HasFactory;

    protected $fillable = ['entry_id', 'user_id', 'content', 'mime_type', 'meta'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function getRenderedContentAttribute(): string
    {
        return Str::of($this->content)->markdown([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ])->toString();
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
