<?php

namespace App\Listeners;

use App\Models\AiLog;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Events\AgentPrompted;
use Laravel\Ai\Events\InvokingTool;
use Laravel\Ai\Events\PromptingAgent;
use Laravel\Ai\Events\ToolInvoked;

class AiEventSubscriber
{
    /**
     * Handle agent prompting events.
     */
    public function handlePromptingAgent(PromptingAgent $event): void
    {
        Log::channel('ai')->info('Prompting agent', [
            'invocation_id' => $event->invocationId,
            'agent' => class_basename($event->prompt->agent),
            'prompt' => $event->prompt->prompt,
        ]);
    }

    /**
     * Handle agent prompted events.
     */
    public function handleAgentPrompted(AgentPrompted $event): void
    {
        $agent = class_basename($event->prompt->agent);
        $response = [
            'text' => $event->response->text ?? null,
            'structured' => $event->response->structured ?? null,
        ];

        Log::channel('ai')->info('Agent prompted', [
            'invocation_id' => $event->invocationId,
            'agent' => $agent,
            'response_text' => $response['text'],
            'structured_output' => $response['structured'],
        ]);

        AiLog::create([
            'invocation_id' => $event->invocationId,
            'type' => 'agent_prompted',
            'agent' => $agent,
            'prompt' => $event->prompt->prompt,
            'response' => $response,
        ]);
    }

    /**
     * Handle tool invoking events.
     */
    public function handleInvokingTool(InvokingTool $event): void
    {
        Log::channel('ai')->info('Invoking tool', [
            'invocation_id' => $event->invocationId,
            'tool_invocation_id' => $event->toolInvocationId,
            'agent' => class_basename($event->agent),
            'tool' => class_basename($event->tool),
            'arguments' => $event->arguments,
        ]);
    }

    /**
     * Handle tool invoked events.
     */
    public function handleToolInvoked(ToolInvoked $event): void
    {
        $agent = class_basename($event->agent);
        $tool = class_basename($event->tool);
        $result = $this->formatResult($event->result);

        Log::channel('ai')->info('Tool invoked', [
            'invocation_id' => $event->invocationId,
            'tool_invocation_id' => $event->toolInvocationId,
            'agent' => $agent,
            'tool' => $tool,
            'arguments' => $event->arguments,
            'result' => $result,
        ]);

        AiLog::create([
            'invocation_id' => $event->invocationId,
            'tool_invocation_id' => $event->toolInvocationId,
            'agent' => $agent,
            'tool' => $tool,
            'arguments' => $event->arguments,
            'result' => is_string($result) ? $result : json_encode($result),
        ]);
    }

    /**
     * Format the tool result for logging.
     */
    protected function formatResult(mixed $result): mixed
    {
        if (is_string($result)) {
            // Truncate long strings
            return strlen($result) > 1000 ? substr($result, 0, 1000).'...' : $result;
        }

        return $result;
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            PromptingAgent::class,
            [self::class, 'handlePromptingAgent']
        );

        $events->listen(
            AgentPrompted::class,
            [self::class, 'handleAgentPrompted']
        );

        $events->listen(
            InvokingTool::class,
            [self::class, 'handleInvokingTool']
        );

        $events->listen(
            ToolInvoked::class,
            [self::class, 'handleToolInvoked']
        );
    }
}
