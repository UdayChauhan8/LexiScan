<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LexiScan') }} - Intelligent Text Analysis</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased text-gray-900 bg-white dark:bg-gray-900 selection:bg-indigo-500 selection:text-white">

    <!-- Navigation -->
    <nav
        class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <!-- Logo Icon -->
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                        L</div>
                    <span class="font-bold text-xl tracking-tight dark:text-white">LexiScan</span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition">Log
                                in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold hover:bg-gray-800 dark:hover:bg-gray-100 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <x-bg-particles />
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-300 text-xs font-semibold uppercase tracking-wide mb-6 animate-fade-in-up">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                New: Share your reports publicly
            </div>
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight mb-8 dark:text-white">
                Clarify your <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">message.</span><br
                    class="hidden sm:block" />
                Amplify your <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">impact.</span>
            </h1>
            <p class="mt-4 text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                LexiScan analyzes your writing for readability, SEO health, and clarity. Get instant feedback and
                actionable insights to write better content, faster.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg shadow-lg hover:shadow-2xl hover:scale-105 transition duration-300">
                    Start Analyzing for Free
                </a>
                <a href="#features"
                    class="w-full sm:w-auto px-8 py-4 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 font-bold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Abstract Background blobs -->
        <div
            class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 overflow-hidden pointer-events-none opacity-40 dark:opacity-20">
            <div
                class="absolute top-[20%] left-[20%] w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-[20%] right-[20%] w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-[20%] left-[40%] w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>
    </div>

    <!-- Dashboard Preview (Image Placeholder) -->
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-32">
        <div
            class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-800 bg-gray-900">
            <div class="absolute top-0 w-full h-8 bg-gray-800/50 flex items-center px-4 gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>
            <!-- Mock UI using HTML/CSS only to avoid needing an image -->
            <div class="pt-8 pb-12 px-8 bg-gray-50 dark:bg-gray-900 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <div class="h-6 w-3/4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 w-full bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 w-full bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 w-5/6 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-bold text-gray-500">Readability</div>
                        <div class="text-green-500 font-bold">Good</div>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">72.4</div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full w-[72%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div id="features" class="py-24 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Everything you
                    need to write flawlessly</h2>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">LexiScan packs powerful analysis tools into a
                    simple, elegant interface.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div
                    class="relative p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Readability Scores</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Instantly calculate Flesch Reading Ease
                        scores to ensure your audience can understand your content effortlessly.</p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="relative p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Content Health</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Get a comprehensive "Health Score" based
                        on length, complexity, and structural variety.</p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="relative p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Public Reports</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Generated uniquely shareable links to
                        send your analysis to clients, editors, or friends.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 justify-center sm:justify-start mb-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                            L</div>
                        <span class="font-bold text-xl dark:text-white">LexiScan</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs mx-auto sm:mx-0">
                        Empowering writers with data-driven insights. Simple, fast, and beautiful text analysis.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-indigo-600 transition">Features</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-indigo-600 transition">Privacy</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-gray-100 dark:border-gray-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} LexiScan. All rights reserved.</p>
                <div class="flex gap-4">
                    <!-- Social placeholders -->
                    <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>