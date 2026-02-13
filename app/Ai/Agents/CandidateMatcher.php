<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchCandidates;
use App\Concerns\HasRoleSkills;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\WebSearch;
use Stringable;

#[Model('gpt-5.2-2025-12-11')]
class CandidateMatcher implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use HasRoleSkills;
    use Promptable;

    /**
     * Create a new agent instance.
     */
    public function __construct(
        protected array $vacancyData,
    ) {}

    /**
     * Get the list of messages comprising the conversation so far.
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return view('agents.candidate-matcher', [
            'vacancyData' => $this->vacancyData,
            'skills' => $this->formatSkills(),
            'skillsByRole' => $this->getSkillsByRoleAsString(),
        ])->render();
    }

    /**
     * Format the skills array as a string.
     */
    protected function formatSkills(): string
    {
        return implode(', ', $this->vacancyData['skills'] ?? []);
    }

    /**
     * Get the tools available to the agent.
     *
     * @return iterable<\Laravel\Ai\Contracts\Tool>
     */
    public function tools(): iterable
    {
        return [
            new WebSearch,
            new SearchCandidates,
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'reasoning' => $schema->string()
                ->description('Detailed explanation of why these candidates were selected, including company research insights and skill analysis')
                ->required(),
            'candidateIds' => $schema->array()
                ->items($schema->integer())
                ->description('Array of maximum 3 candidate IDs representing the best matches, empty if no suitable candidates found')
                ->required(),
        ];
    }
}
