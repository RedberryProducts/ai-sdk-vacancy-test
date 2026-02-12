<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Matching Candidates - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6 lg:p-8 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-medium">Matching Candidates</h1>
            <a
                href="{{ route('vacancy.index') }}"
                class="inline-block px-5 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm text-sm"
            >
                Upload Another
            </a>
        </div>

        <p class="mb-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Found {{ $candidates->count() }} matching candidates for your vacancy.
        </p>

        <div class="space-y-4">
            @foreach ($candidates as $candidate)
                <div class="p-6 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-medium">{{ $candidate->name }}</h2>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">
                                {{ $candidate->seniority->label() }} {{ $candidate->role->label() }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($candidate->skills as $skill)
                                <span class="inline-block px-3 py-1 text-xs bg-[#dbdbd7] dark:bg-[#3E3E3A] rounded-full">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
