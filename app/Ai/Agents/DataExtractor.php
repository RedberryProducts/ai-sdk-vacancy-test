<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;
use App\Seniority;

class DataExtractor implements Agent, Conversational, HasTools, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Extract structured data from the provided vacancy PDF and return it in the specified format.';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'company' => $schema->string()->required(),
            'seniority' => $schema->string()->enum(Seniority::cases())->required(),
            'role' => $schema->string()->required(),
            'skills' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
