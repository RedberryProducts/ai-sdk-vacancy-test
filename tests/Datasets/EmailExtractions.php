<?php

use Tests\Support\EvalCase;

/*
|--------------------------------------------------------------------------
| Simple Dataset (inline values)
|--------------------------------------------------------------------------
*/
dataset('email_extractions_simple', [
    'extraction_1' => [
        'Does this text contain an email address? If so, extract it.',
        'Yes, the email address is: example@example.com',
    ],
]);

/*
|--------------------------------------------------------------------------
| Simple Dataset with file paths (no DTO)
|--------------------------------------------------------------------------
|
| Use this approach for simple path-based datasets where you handle
| loading in the test itself. Order: input, expectedOutput, cachedOutput.
|
*/
dataset('email_extractions_paths', [
    'email_1' => [
        'evals/DataExtractor/email_1/input.json',
        'evals/DataExtractor/email_1/expectedOutput.json',
    ],
    'email_2' => [
        'evals/DataExtractor/email_2/input.json',
        'evals/DataExtractor/email_2/expectedOutput.json',
        'evals/DataExtractor/email_2/cachedOutput.json',
    ],
]);

/*
|--------------------------------------------------------------------------
| Dataset with EvalCase DTO (inline data)
|--------------------------------------------------------------------------
*/
dataset('email_extractions', [
    'extraction_1' => fn () => EvalCase::fromData(
        input: [
            ['role' => 'system', 'content' => 'Extract structured data from the provided vacancy email.'],
            ['role' => 'user', 'content' => "Subject: Job Opening: Senior Software Engineer\n\nWe are looking for a Senior Software Engineer at TechCorp."],
        ],
        expectedOutput: [
            'tool_calls' => '[{"name":"parse_email","arguments":{"format":"vacancy"}}]',
            'final_response' => '{"company":"TechCorp","role":"Software Engineer","seniority":"senior","skills":[]}',
        ],
    ),

    'extraction_2_with_cache' => fn () => EvalCase::fromData(
        input: [
            ['role' => 'system', 'content' => 'Extract structured data from the provided vacancy email.'],
            ['role' => 'user', 'content' => "Subject: Junior Developer Position at StartupXYZ\n\nStartupXYZ is hiring a Junior Developer. Skills: PHP, Laravel."],
        ],
        expectedOutput: [
            'tool_calls' => '[{"name":"parse_email","arguments":{"format":"vacancy"}}]',
            'final_response' => '{"company":"StartupXYZ","role":"Developer","seniority":"junior","skills":["PHP","Laravel"]}',
        ],
        cachedOutput: [
            'tool_calls' => '[{"name":"parse_email","arguments":{"format":"vacancy"}}]',
            'final_response' => '{"company":"StartupXYZ","role":"Developer","seniority":"junior","skills":["PHP","Laravel"]}',
        ],
    ),
]);

/*
|--------------------------------------------------------------------------
| Dataset with EvalCase DTO (from JSON files)
|--------------------------------------------------------------------------
|
| Use this approach when you have larger test cases stored in JSON files.
| Paths are relative to the tests/ directory.
|
*/
dataset('email_extractions_from_files', [
    'email_1' => fn () => EvalCase::fromPaths(
        inputPath: 'evals/DataExtractor/email_1/input.json',
        expectedOutputPath: 'evals/DataExtractor/email_1/expectedOutput.json',
    ),

    'email_2_with_cache' => fn () => EvalCase::fromPaths(
        inputPath: 'evals/DataExtractor/email_2/input.json',
        expectedOutputPath: 'evals/DataExtractor/email_2/expectedOutput.json',
        cachedOutputPath: 'evals/DataExtractor/email_2/cachedOutput.json',
    ),
]);

/*
|--------------------------------------------------------------------------
| Dataset with single evalCase.json file
|--------------------------------------------------------------------------
|
| Use this approach when you want all eval data in a single file.
| The JSON file contains: input, expected_output, and cached_output.
|
*/
dataset('email_extractions_single_file', [
    'email_3' => fn () => EvalCase::fromPath('evals/DataExtractor/email_3/evalCase.json'),
]);

/*
|--------------------------------------------------------------------------
| Dataset with tool_calls and final_response as array
|--------------------------------------------------------------------------
|
| This dataset demonstrates:
| - tool_calls: array of tool invocations made by the agent
| - final_response: array format (alternative to JSON string)
|
*/
dataset('candidate_matching', [
    'match_with_tools' => fn () => EvalCase::fromData(
        input: [
            ['role' => 'system', 'content' => 'Find candidates matching the job requirements. Use the search_candidates tool.'],
            ['role' => 'user', 'content' => 'Find senior PHP developers with Laravel experience.'],
        ],
        expectedOutput: [
            'tool_calls' => [
                [
                    'name' => 'search_candidates',
                    'arguments' => [
                        'skills' => ['PHP', 'Laravel'],
                        'seniority' => 'senior',
                    ],
                ],
            ],
            'final_response' => [
                'matched_candidates' => 3,
                'top_match' => [
                    'name' => 'John Doe',
                    'score' => 0.95,
                ],
            ],
        ],
        cachedOutput: [
            'tool_calls' => [
                [
                    'name' => 'search_candidates',
                    'arguments' => [
                        'skills' => ['PHP', 'Laravel'],
                        'seniority' => 'senior',
                    ],
                ],
            ],
            'final_response' => [
                'matched_candidates' => 3,
                'top_match' => [
                    'name' => 'John Doe',
                    'score' => 0.95,
                ],
            ],
        ],
    ),

    'multiple_tool_calls' => fn () => EvalCase::fromData(
        input: [
            ['role' => 'system', 'content' => 'Search for candidates and verify their availability.'],
            ['role' => 'user', 'content' => 'Find available Vue.js developers.'],
        ],
        expectedOutput: [
            'tool_calls' => [
                [
                    'name' => 'search_candidates',
                    'arguments' => [
                        'skills' => ['Vue.js'],
                    ],
                ],
                [
                    'name' => 'check_availability',
                    'arguments' => [
                        'candidate_ids' => [1, 2, 3],
                    ],
                ],
            ],
            'final_response' => [
                'available_candidates' => 2,
                'candidates' => [
                    ['id' => 1, 'name' => 'Jane Smith', 'available' => true],
                    ['id' => 3, 'name' => 'Bob Wilson', 'available' => true],
                ],
            ],
        ],
        cachedOutput: [
            'tool_calls' => [
                [
                    'name' => 'search_candidates',
                    'arguments' => [
                        'skills' => ['Vue.js'],
                    ],
                ],
                [
                    'name' => 'check_availability',
                    'arguments' => [
                        'candidate_ids' => [1, 2, 3],
                    ],
                ],
            ],
            'final_response' => [
                'available_candidates' => 2,
                'candidates' => [
                    ['id' => 1, 'name' => 'Jane Smith', 'available' => true],
                    ['id' => 3, 'name' => 'Bob Wilson', 'available' => true],
                ],
            ],
        ],
    ),
]);
