<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invocation_id',
        'type',
        'tool_invocation_id',
        'agent',
        'tool',
        'prompt',
        'arguments',
        'result',
        'response',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'response' => 'array',
        ];
    }
}
