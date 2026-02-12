<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Ai\Agents\DataExtractor;

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
            'Analyze the attached sales transcript...',
            attachments: [
                $request->file('vacancy_pdf'),
            ]
        );

        dd($vacancy->structured);

        // TODO: Match Vacancy to candidate
        $candidates = Candidate::inRandomOrder()->take(5)->get();

        return view('vacancy.results', [
            'candidates' => $candidates,
            'vacancyPath' => $path,
        ]);
    }
}
