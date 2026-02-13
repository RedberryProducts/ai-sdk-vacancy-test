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
            @forelse ($candidates as $candidate)
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
            @empty
                <div class="p-6 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-center">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">No matching candidates found.</p>
                </div>
            @endforelse
        </div>

        @if($logs->isNotEmpty())
        <details class="mt-8 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
            <summary class="p-6 cursor-pointer select-none hover:bg-[#f5f5f4] dark:hover:bg-[#1c1c1b] transition-colors">
                <span class="text-lg font-medium">AI Activity Trace</span>
                <span class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">({{ $logs->count() }} events)</span>
            </summary>
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                @foreach($logs as $log)
                <div class="p-4 {{ !$loop->last ? 'border-b border-[#e3e3e0] dark:border-[#3E3E3A]' : '' }}">
                    @if($log->type === 'agent_prompted')
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#dbeafe] dark:bg-[#1e3a5f] text-[#1e40af] dark:text-[#93c5fd] rounded">Agent Response</span>
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $log->agent }}</span>
                        </div>
                        <div class="mb-2">
                            <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Prompt:</p>
                            <pre class="text-xs bg-[#f5f5f4] dark:bg-[#0a0a0a] p-2 rounded overflow-x-auto"><code>{{ $log->prompt }}</code></pre>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Response:</p>
                            <pre class="text-xs bg-[#f5f5f4] dark:bg-[#0a0a0a] p-2 rounded overflow-x-auto max-h-48 overflow-y-auto"><code>{{ json_encode($log->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    @else
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#dbdbd7] dark:bg-[#3E3E3A] rounded">{{ $log->tool }}</span>
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">via {{ $log->agent }}</span>
                        </div>
                        <div class="mb-2">
                            <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Arguments:</p>
                            <pre class="text-xs bg-[#f5f5f4] dark:bg-[#0a0a0a] p-2 rounded overflow-x-auto"><code>{{ json_encode($log->arguments, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Result:</p>
                            <pre class="text-xs bg-[#f5f5f4] dark:bg-[#0a0a0a] p-2 rounded overflow-x-auto max-h-48 overflow-y-auto"><code>{{ $log->result }}</code></pre>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </details>
        @endif   
        
        <div class="mt-8 p-6 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
            <h2 class="text-lg font-medium mb-4">AI Reasoning</h2>
            <div class="reasoning-content text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-4 [&>h2]:text-lg [&>h2]:font-semibold [&>h2]:text-[#1b1b18] [&>h2]:dark:text-[#EDEDEC] [&>h2]:mt-6 [&>h2]:mb-3 [&>h3]:text-base [&>h3]:font-semibold [&>h3]:text-[#1b1b18] [&>h3]:dark:text-[#EDEDEC] [&>h3]:mt-4 [&>h3]:mb-2 [&>ol]:list-decimal [&>ol]:pl-5 [&>ul]:list-disc [&>ul]:pl-5 [&>strong]:text-[#1b1b18] [&>strong]:dark:text-[#EDEDEC]">
                {!! Str::markdown($reasoning) !!}
            </div>
        </div>

    </div>
</body>
</html>
