<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $analysis->title }} - Analysis Report</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased text-gray-900 antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col pt-6 sm:pt-0">
        <div class="w-full max-w-5xl mx-auto p-6 lg:p-12">
            <!-- Header -->
            <div class="mb-10 text-center">
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600">
                    LexiScan Report
                </h1>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Analysis results for <span
                        class="font-semibold text-gray-700 dark:text-gray-200">"{{ $analysis->title }}"</span></p>
                <p class="text-sm text-gray-400 mt-1">Generated on {{ $analysis->created_at->format('M d, Y') }}</p>
            </div>

            <!-- Main Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <!-- Word Count -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border-b-4 border-blue-500">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Word Count</div>
                    <div class="mt-3 text-4xl font-extrabold">{{ number_format($analysis->word_count) }}</div>
                </div>

                <!-- Flesch Score -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border-b-4 border-green-500">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Readability</div>
                    <div class="mt-3 text-4xl font-extrabold">{{ $analysis->readability_score }}</div>
                    <div class="text-xs text-gray-400 mt-1">Flesch Reading Ease</div>
                </div>

                <!-- Health Score -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border-b-4 border-indigo-500">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Health Score</div>
                    <div class="mt-3 text-4xl font-extrabold">{{ $analysis->content_health_score }}%</div>
                </div>

                <!-- Avg Sentence -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border-b-4 border-orange-500">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Avg. Length</div>
                    <div class="mt-3 text-4xl font-extrabold">{{ $analysis->avg_sentence_length }}</div>
                    <div class="text-xs text-gray-400 mt-1">Words / Sentence</div>
                </div>
            </div>

            <!-- Content & Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Analyzed Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                            <h3 class="font-bold text-gray-700 dark:text-gray-200">Analyzed Text</h3>
                        </div>
                        <div
                            class="p-6 font-serif leading-loose text-lg text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                            {{ $analysis->content_raw }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-2">LexiScan Analysis</h3>
                        <p class="opacity-90 text-sm mb-4">
                            This content was analyzed using LexiScan's advanced algorithms for readability and SEO
                            health.
                        </p>
                        <a href="/"
                            class="inline-block bg-white text-indigo-600 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition">
                            Create Your Own
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
                        <h4 class="font-bold text-gray-700 dark:text-gray-200 mb-3">Targeting</h4>
                        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>Keyword:</span>
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $analysis->target_keyword ?: 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} LexiScan. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>