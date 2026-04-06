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
use Laravel\Ai\Attributes\MaxSteps;

class CandidatesMatcher implements Agent, Conversational, HasTools
{
    use HasRoleSkills;
    use Promptable;

    protected array $messages = [];

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

    public function __construct(
        protected array $vacancyData,
    ) {}

    /**
     * Format the skills array as a string.
     */
    protected function formatSkills(): string
    {
        return implode(', ', $this->vacancyData['skills'] ?? []);
    }

    /**
     * Get the list of messages comprising the conversation so far.
     */
    public function messages(): iterable
    {
        return $this->messages ?? [];
    }

    public function withMessages(array $messages): static
    {
        $this->messages = $messages;
        // dd($this->messages);
        return $this;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
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
