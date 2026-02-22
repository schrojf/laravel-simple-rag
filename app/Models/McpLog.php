<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class McpLog extends Model
{
    protected $fillable = ['user_id', 'session_id', 'primitive_type', 'primitive_name', 'input'];

    protected function casts(): array
    {
        return [
            'input' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
