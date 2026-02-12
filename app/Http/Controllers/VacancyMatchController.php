<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $path = $request->file('vacancy_pdf')->store('vacancies', 'local');

        // TODO: Match Vacancy to candidate
        $candidates = Candidate::inRandomOrder()->take(5)->get();

        return view('vacancy.results', [
            'candidates' => $candidates,
            'vacancyPath' => $path,
        ]);
    }
}
