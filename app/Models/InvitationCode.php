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

    public static function generateCode(): string
    {
        do {
            $code = static::generateSegment().'-'.static::generateSegment().'-'.static::generateSegment();
        } while (static::where('code', $code)->exists());

        return $code;
    }

    private static function generateSegment(): string
    {
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $segment = '';

        for ($i = 0; $i < 3; $i++) {
            $segment .= $charset[random_int(0, strlen($charset) - 1)];
        }

        return $segment;
    }
}
