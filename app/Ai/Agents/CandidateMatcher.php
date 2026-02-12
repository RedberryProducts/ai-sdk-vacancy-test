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
        return <<<INSTRUCTIONS
        You are a recruitment assistant that matches job vacancies to candidates.

        You have been given vacancy data extracted from a PDF. Your task is to:
        1. Use the web search tool to find information about the company to better understand their culture and needs.
        2. Analyze the required skills and determine which ONE skill is the most important for this role.
        3. Use the search candidates tool to find matching candidates based on role, seniority, and the single most important skill.
        4. Select a maximum of 3 best matching candidates and provide detailed reasoning for your choices.
        5. If no suitable candidates are found, return an empty array and explain why.

        Vacancy Data:
        - Company: {$this->vacancyData['company']}
        - Role: {$this->vacancyData['role']}
        - Seniority: {$this->vacancyData['seniority']}
        - Required Skills: {$this->formatSkills()}

        Available Skills by Role:
        {$this->getSkillsByRoleAsString()}
        INSTRUCTIONS;
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
