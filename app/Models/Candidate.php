<?php

namespace App\Models;

use App\Role;
use App\Seniority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'seniority',
        'skills',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'seniority' => Seniority::class,
            'skills' => 'array',
        ];
    }
}
