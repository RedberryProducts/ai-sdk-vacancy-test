<?php

use App\Http\Controllers\VacancyMatchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Ai\Agents\DataExtractor;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vacancy', [VacancyMatchController::class, 'index'])->name('vacancy.index');
Route::post('/vacancy/match', [VacancyMatchController::class, 'match'])->name('vacancy.match');

Route::get('/test-agent', function () {
    // Check if file exists
    if (Storage::exists('cv/sample_resume.md')) {
        $content = Storage::get('cv/sample_resume.md');
    }

    $response = (new DataExtractor)->prompt('Review following resume and extract key information: ' . $content);

    // Array
    dd($response);

    return $response;
});