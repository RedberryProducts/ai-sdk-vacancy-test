<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Match Vacancy - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <div class="w-full max-w-md">
        <h1 class="text-2xl font-medium mb-6 text-center">Match Vacancy to Candidates</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-[#fff2f2] dark:bg-[#1D0002] rounded-sm border border-[#F53003] dark:border-[#FF4433]">
                <ul class="text-sm text-[#F53003] dark:text-[#FF4433]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vacancy.match') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="vacancy_pdf" class="block text-sm font-medium mb-2">Upload Vacancy PDF</label>
                <input
                    type="file"
                    id="vacancy_pdf"
                    name="vacancy_pdf"
                    accept=".pdf"
                    required
                    class="block w-full text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-3 bg-white dark:bg-[#161615] file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-medium file:bg-[#1b1b18] file:text-white dark:file:bg-[#eeeeec] dark:file:text-[#1C1C1A] hover:file:opacity-80"
                >
            </div>

            <button
                type="submit"
                class="w-full py-3 px-5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] font-medium rounded-sm hover:opacity-90 transition-opacity"
            >
                Find Matching Candidates
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Upload a PDF job description to find the best matching candidates.
        </p>
    </div>
</body>
</html>
