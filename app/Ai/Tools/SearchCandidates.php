<?php

namespace App\Ai\Tools;

use App\Models\Candidate;
use App\Role;
use App\Seniority;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchCandidates implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search for candidates matching the vacancy requirements by role, seniority, and skills.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = Candidate::query();

        if ($request->has('role')) {
            $query->where('role', $request['role']);
        }

        if ($request->has('seniority')) {
            $query->where('seniority', $request['seniority']);
        }

        if ($request->has('skill')) {
            $query->whereJsonContains('skills', $request['skill']);
        }

        $candidates = $query->limit(10)->get();

        if ($candidates->isEmpty()) {
            return 'No candidates found matching the criteria.';
        }

        return $candidates->map(fn (Candidate $candidate) => sprintf(
            'ID: %d, Name: %s, Role: %s, Seniority: %s, Skills: %s',
            $candidate->id,
            $candidate->name,
            $candidate->role->label(),
            $candidate->seniority->label(),
            implode(', ', $candidate->skills)
        ))->implode("\n");
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'role' => $schema->string()
                ->enum(Role::cases())
                ->description('Filter by role'),
            'seniority' => $schema->string()
                ->enum(Seniority::cases())
                ->description('Filter by seniority level'),
            'skill' => $schema->string()
                ->description('Filter by the most important skill for this vacancy'),
        ];
    }
}
