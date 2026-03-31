<?php

use App\Ai\Agents\DataExtractor;
use Tests\Support\EvalCase;

it('extracts data from email correctly', function (EvalCase $eval) {
    // Use cached output if available, otherwise run the agent
    if ($eval->hasCachedOutput()) {
        $result = $eval->cachedOutput;
    } else {
        // TODO: Run the DataExtractor agent when AI is configured
        // $agent = new DataExtractor();
        // $result = ai()->send($agent, $eval->input['email_body'])->structured();
        $this->markTestSkipped('Cached output not available and AI agent not configured');
    }

    // Assert the extracted data matches expected output
    expect($result)->toMatchArray($eval->expectedOutput);
})->with('email_extractions');

/*
|--------------------------------------------------------------------------
| Example with inline dataset using EvalCase
|--------------------------------------------------------------------------
*/
it('can extract email from text', function (EvalCase $eval) {
    if ($eval->hasCachedOutput()) {
        $result = $eval->cachedOutput;
    } else {
        $this->markTestSkipped('Cached output not available');
    }

    expect($result)->toMatchArray($eval->expectedOutput);
})->with([
    'inline_example' => fn () => EvalCase::fromData(
        input: [
            ['role' => 'user', 'content' => 'Contact us at hello@example.com'],
        ],
        expectedOutput: [
            'tool_calls' => [],
            'final_response' => '{"email":"hello@example.com"}',
        ],
        cachedOutput: [
            'tool_calls' => [],
            'final_response' => '{"email":"hello@example.com"}',
        ],
    ),
]);

/*
|--------------------------------------------------------------------------
| Example with dataset loaded from JSON files
|--------------------------------------------------------------------------
*/
it('extracts data from email files', function (EvalCase $eval) {
    if ($eval->hasCachedOutput()) {
        $result = $eval->cachedOutput;
    } else {
        $this->markTestSkipped('Cached output not available');
    }

    expect($result)->toMatchArray($eval->expectedOutput);
})->with('email_extractions_from_files');

/*
|--------------------------------------------------------------------------
| Example with single evalCase.json file
|--------------------------------------------------------------------------
*/
it('extracts data from single eval file', function (EvalCase $eval) {
    if ($eval->hasCachedOutput()) {
        $result = $eval->cachedOutput;
    } else {
        $this->markTestSkipped('Cached output not available');
    }

    expect($result)->toMatchArray($eval->expectedOutput);
})->with('email_extractions_single_file');

/*
|--------------------------------------------------------------------------
| Example with tool_calls and final_response as array
|--------------------------------------------------------------------------
*/
it('matches candidates using tools', function (EvalCase $eval) {
    if ($eval->hasCachedOutput()) {
        $result = $eval->cachedOutput;
    } else {
        $this->markTestSkipped('Cached output not available');
    }

    // Assert tool calls match
    expect($result['tool_calls'])->toMatchArray($eval->expectedOutput['tool_calls']);

    // Assert final response matches (array format)
    expect($result['final_response'])->toMatchArray($eval->expectedOutput['final_response']);
})->with('candidate_matching');

/*
|--------------------------------------------------------------------------
| Example with simple file paths (no DTO)
|--------------------------------------------------------------------------
*/
it('extracts data using path strings', function (string $inputPath, string $expectedOutputPath, ?string $cachedOutputPath = null) {
    // Load files manually
    $input = json_decode(file_get_contents(base_path("tests/{$inputPath}")), true);
    $expectedOutput = json_decode(file_get_contents(base_path("tests/{$expectedOutputPath}")), true);
    $cachedOutput = $cachedOutputPath
        ? json_decode(file_get_contents(base_path("tests/{$cachedOutputPath}")), true)
        : null;

    if ($cachedOutput === null) {
        $this->markTestSkipped('Cached output not available');
    }

    expect($cachedOutput)->toMatchArray($expectedOutput);
})->with('email_extractions_paths');
