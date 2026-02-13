<?php

namespace App\Http\Controllers;

use App\Ai\Agents\CandidateMatcher;
use App\Ai\Agents\DataExtractor;
use App\Models\AiLog;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
