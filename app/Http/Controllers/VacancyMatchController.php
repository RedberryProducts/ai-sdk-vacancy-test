<?php

namespace App\Http\Controllers;

use App\Ai\Agents\CandidatesMatcher;
use App\Ai\Agents\DataExtractor;
use App\Models\AiLog;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Ai\Responses\Data\ToolResult;
use Laravel\Ai\Responses\Data\ToolCall;
use Laravel\Ai\Messages\ToolResultMessage;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Messages\AssistantMessage;

class VacancyMatchController extends Controller
{
    public function index(): View
    {
        return view('vacancy.match');
    }

    public function match(Request $request): View
    {
        $request->validate([
            'vacancy_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        // AI Agents here
        $vacancy = (new DataExtractor)->prompt(
            'Extract the vacancy details from the attached PDF.',
            attachments: [
                $request->file('vacancy_pdf'),
            ]
        );

        $result = CandidatesMatcher::make($vacancy->structured)->prompt('Find the best matching candidates for this vacancy.');


        $toolResult = "ID: 172, Name: Carol Douglas DDS, Role: Project Manager, Seniority: Senior, Skills: Team Leadership, Confluence, Budgeting, Agile";
        $messages = [
            new UserMessage('Find the best matching candidates for this vacancy.'),
            new AssistantMessage('', collect([$result->toolCalls[0]])),
            new ToolResultMessage(
                collect([new ToolResult(
                    name: $result->toolCalls[0]->name,
                    id: $result->toolCalls[0]->id,
                    arguments: $result->toolCalls[0]->arguments,
                    result: $toolResult,
                )])
            ),
        ];
        $result = CandidatesMatcher::make($vacancy->structured)->withMessages($messages)->prompt('Tell me fun story');
        dd($result);

        $candidates = Candidate::whereIn('id', $result->structured['candidateIds'] ?? [])->get();

        $logs = AiLog::whereIn('invocation_id', [$vacancy->invocationId, $result->invocationId])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('vacancy.results', [
            'vacancy' => $vacancy->structured,
            'reasoning' => $result->structured['reasoning'] ?? 'No candidates found.',
            'candidates' => $candidates,
            'logs' => $logs,
        ]);
    }
}
