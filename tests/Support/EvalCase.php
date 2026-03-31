<?php

namespace Tests\Support;

use InvalidArgumentException;

/**
 * Data Transfer Object for evaluation test cases.
 *
 * Contains the input, expected output, and optional cached output
 * for AI agent evaluation tests.
 */
readonly class EvalCase
{
    /**
     * @param  array<string, mixed>|null  $input  The input data for the evaluation
     * @param  array<string, mixed>|null  $expectedOutput  The expected output to compare against
     * @param  array<string, mixed>|null  $cachedOutput  Optional cached AI response to use instead of calling the API
     */
    public function __construct(
        public ?array $input = null,
        public ?array $expectedOutput = null,
        public ?array $cachedOutput = null,
        private ?string $inputPath = null,
        private ?string $expectedOutputPath = null,
        private ?string $cachedOutputPath = null,
    ) {}

    /**
     * Create an EvalCase from a single JSON file containing all properties.
     *
     * The JSON file should have: input, expected_output, and optionally cached_output.
     * Path is relative to the tests/ directory.
     */
    public static function fromPath(string $path): self
    {
        $data = self::loadJson($path);

        return new self(
            input: $data['input'] ?? null,
            expectedOutput: $data['expected_output'] ?? null,
            cachedOutput: $data['cached_output'] ?? null,
        );
    }

    /**
     * Create an EvalCase from JSON file paths.
     *
     * Paths are relative to the tests/ directory.
     */
    public static function fromPaths(
        string $inputPath,
        string $expectedOutputPath,
        ?string $cachedOutputPath = null,
    ): self {
        return new self(
            input: self::loadJson($inputPath),
            expectedOutput: self::loadJson($expectedOutputPath),
            cachedOutput: $cachedOutputPath ? self::loadJson($cachedOutputPath) : null,
            inputPath: $inputPath,
            expectedOutputPath: $expectedOutputPath,
            cachedOutputPath: $cachedOutputPath,
        );
    }

    /**
     * Create an EvalCase from inline data.
     *
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $expectedOutput
     * @param  array<string, mixed>|null  $cachedOutput
     */
    public static function fromData(
        array $input,
        array $expectedOutput,
        ?array $cachedOutput = null,
    ): self {
        return new self(
            input: $input,
            expectedOutput: $expectedOutput,
            cachedOutput: $cachedOutput,
        );
    }

    /**
     * Check if cached output is available.
     */
    public function hasCachedOutput(): bool
    {
        return $this->cachedOutput !== null;
    }

    /**
     * Get the result to use for testing (cached if available).
     *
     * @return array<string, mixed>|null
     */
    public function getTestableOutput(): ?array
    {
        return $this->cachedOutput;
    }

    /**
     * Load JSON from a file path relative to tests/ directory.
     *
     * @return array<string, mixed>
     */
    private static function loadJson(string $path): array
    {
        $fullPath = base_path("tests/{$path}");

        if (! file_exists($fullPath)) {
            throw new InvalidArgumentException("Eval file not found: {$fullPath}");
        }

        $content = file_get_contents($fullPath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON in {$fullPath}: ".json_last_error_msg());
        }

        return $data;
    }
}
