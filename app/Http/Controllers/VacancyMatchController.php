<?php

namespace App\Http\Controllers;

use App\Ai\Agents\CandidateMatcher;
use App\Ai\Agents\DataExtractor;
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

        $vacancy = (new DataExtractor)->prompt(
            'Extract the vacancy details from the attached PDF.',
            attachments: [
                $request->file('vacancy_pdf'),
            ]
        );

        $result = CandidateMatcher::make($vacancy->structured)->prompt('Find the best candidates for this vacancy.');

        $candidates = Candidate::whereIn('id', $result->structured['candidateIds'] ?? [])->get();

        return view('vacancy.results', [
            'vacancy' => $vacancy->structured,
            'reasoning' => $result->structured['reasoning'] ?? 'No candidates found.',
            'candidates' => $candidates,
        ]);
    }
}
