<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationCode extends Model
{
    protected $fillable = ['code', 'active', 'used_at', 'used_by', 'description'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'used_at' => 'datetime',
            'used_by' => 'integer',
        ];
    }
}
