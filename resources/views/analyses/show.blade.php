<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </span>
                {{ $analysis->title ?: 'Untitled Analysis' }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 font-mono">{{ $analysis->created_at->format('M d, Y') }}</span>
                <a href="{{ route('analyses.edit', $analysis) }}"
                    class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors"
                    title="Edit Analysis">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </a>
                <form action="{{ route('analyses.destroy', $analysis) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this analysis?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                        title="Delete Analysis">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative">

            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="absolute top-0 right-0 m-6 z-50 flex items-center gap-3 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-500 ease-out"
                    x-transition:enter="translate-y-[-20px] opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="translate-y-[-20px] opacity-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('status') }}</span>
                    <button @click="show = false" class="ml-2 text-emerald-200 hover:text-white"><svg class="w-4 h-4"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg></button>
                </div>
            @endif

            <!-- Top Grid: Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Word Count -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6 relative group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Word
                        Count</div>
                    <div
                        class="mt-1 text-4xl font-extrabold text-gray-900 dark:text-white group-hover:text-indigo-500 transition-colors">
                        {{ number_format($analysis->word_count) }}
                    </div>
                    <div class="mt-2 text-xs text-gray-400 font-medium">approx. {{ ceil($analysis->word_count / 200) }}
                        min read</div>
                </div>

                <!-- Readability Score -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6 relative">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Readability</div>
                        <div
                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                            Flesch</div>
                    </div>

                    <div class="flex items-end gap-2 mt-1">
                        <span
                            class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $analysis->readability_score }}</span>
                        <span class="text-sm text-gray-400 mb-1">/ 100</span>
                    </div>

                    <!-- Gradient Progress Bar -->
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 mt-4 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-indigo-600 shadow-[0_0_10px_rgba(79,70,229,0.5)]"
                            style="width: {{ min(100, $analysis->readability_score) }}%"></div>
                    </div>
                </div>

                <!-- Content Health -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6">
                    <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">
                        Content Health</div>
                    <div class="flex items-center gap-3 mt-2">
                        <!-- Circular Progress (CSS only) -->
                        <div class="relative w-14 h-14">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="6" fill="transparent"
                                    class="text-gray-200 dark:text-gray-700" />
                                <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="6" fill="transparent"
                                    class="{{ $analysis->content_health_score > 70 ? 'text-green-500' : ($analysis->content_health_score > 40 ? 'text-yellow-500' : 'text-red-500') }}"
                                    stroke-dasharray="150.72"
                                    stroke-dashoffset="{{ 150.72 - (150.72 * $analysis->content_health_score / 100) }}"
                                    stroke-linecap="round" />
                            </svg>
                            <span
                                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-xs font-bold dark:text-white">
                                {{ $analysis->content_health_score }}
                            </span>
                        </div>
                        <div>
                            <div class="font-bold dark:text-white">
                                @if($analysis->content_health_score > 80) Excellent
                                @elseif($analysis->content_health_score > 60) Good
                                @elseif($analysis->content_health_score > 40) Fair
                                @else Poor
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">Based on standard metrics</div>
                        </div>
                    </div>
                </div>

                <!-- Avg Sentence Length -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6">
                    <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Avg
                        Length</div>
                    <div class="mt-1 text-4xl font-extrabold text-gray-900 dark:text-white">
                        {{ $analysis->avg_sentence_length }}
                    </div>
                    <div class="mt-2 text-xs text-gray-400 font-medium">words per sentence</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Content Review -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col h-full">
                        <div
                            class="px-8 py-6 border-b border-gray-100 dark:border-gray-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Content Review
                            </h3>
                            <button
                                onclick="navigator.clipboard.writeText(`{{ addslashes($analysis->content_raw) }}`); alert('Copied to clipboard!');"
                                class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Copy Text
                            </button>
                        </div>
                        <div class="p-8 bg-white dark:bg-gray-800 flex-grow">
                            <div
                                class="font-serif leading-loose text-lg text-gray-700 dark:text-gray-300 whitespace-pre-wrap max-w-none">
                                {{ $analysis->content_raw }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Tools & Insights -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Score Context Card -->
                    <div x-data="{
                        selected: 'marketing',
                        score: {{ $analysis->readability_score }},
                        categories: {
                            lifestyle: {
                                label: 'Simple Blog / Lifestyle',
                                min: 65,
                                max: 75,
                                desc: 'Optimized for general audiences reading for leisure.',
                                rationale: 'Uses conversational language and simple sentence structures.'
                            },
                            marketing: {
                                label: 'Marketing / SEO Blog',
                                min: 55,
                                max: 65,
                                desc: 'Optimized for adult readers and search engines.',
                                rationale: 'Uses professional vocabulary and persuasive language.'
                            },
                            business: {
                                label: 'Business / Professional',
                                min: 45,
                                max: 60,
                                desc: 'Optimized for business comms and reports.',
                                rationale: 'Prioritizes precision and professional terminology over simplicity.'
                            },
                            technical: {
                                label: 'Technical / Educational',
                                min: 30,
                                max: 50,
                                desc: 'Optimized for specialized knowledge transfer.',
                                rationale: 'Requires complex sentences to explain detailed concepts.'
                            },
                            beginner: {
                                label: 'Child / Beginner',
                                min: 80,
                                max: 95,
                                desc: 'Optimized for early readers or language learners.',
                                rationale: 'Uses very short sentences and basic vocabulary.'
                            }
                        },
                        getStatus() {
                            const cat = this.categories[this.selected];
                            if (this.score < cat.min) return 'below';
                            if (this.score > cat.max) return 'above';
                            return 'within';
                        }
                    }"
                        class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                            <h3 class="font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                Score Context
                                <span
                                    class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-600 text-[10px] uppercase font-bold">New</span>
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Content
                                    Type</label>
                                <select x-model="selected"
                                    class="w-full text-sm bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <template x-for="(cat, key) in categories" :key="key">
                                        <option :value="key" x-text="cat.label"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition-colors duration-300">
                                <div class="flex justify-between items-baseline mb-2">
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Ideal
                                        Range</span>
                                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400"
                                        x-text="`${categories[selected].min} – ${categories[selected].max}`"></span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-2"
                                    x-text="categories[selected].desc"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic"
                                    x-text="categories[selected].rationale"></p>
                            </div>

                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700/50">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Your
                                        Score</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="score"></span>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div x-show="getStatus() === 'within'" class="flex-shrink-0 text-emerald-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div x-show="getStatus() !== 'within'" class="flex-shrink-0 text-amber-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold" :class="{
                                               'text-emerald-600 dark:text-emerald-400': getStatus() === 'within',
                                               'text-amber-600 dark:text-amber-400': getStatus() !== 'within'
                                           }"
                                            x-text="getStatus() === 'within' ? 'Within typical range' : (getStatus() === 'above' ? 'Above typical range' : 'Below typical range')">
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-show="getStatus() === 'above'">This may indicate overly simple language
                                            for this content type.</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-show="getStatus() === 'below'">This may indicate dense sentences or
                                            complex wording.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Card -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                            <h3 class="font-bold text-gray-900 dark:text-gray-100">Share Report</h3>
                        </div>
                        <div class="p-6">
                            @if($analysis->report)
                                <div class="mb-4">
                                    <label
                                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Public
                                        Link</label>
                                    <div class="flex shadow-sm">
                                        <input type="text" readonly
                                            value="{{ route('reports.show', $analysis->report->public_link_token) }}"
                                            onclick="this.select()"
                                            class="w-full text-sm bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <a href="{{ route('reports.show', $analysis->report->public_link_token) }}"
                                            target="_blank"
                                            class="inline-flex items-center px-4 bg-gray-100 dark:bg-gray-700 border border-l-0 border-gray-200 dark:border-gray-600 rounded-r-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <form action="{{ route('reports.destroy', $analysis) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full text-center text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-wider transition-colors py-2 rounded border border-transparent hover:border-red-200 dark:hover:border-red-900/30">Revoke
                                        Link</button>
                                </form>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Generate a secure,
                                    public link to share this analysis with clients or colleagues.</p>
                                <form action="{{ route('reports.store', $analysis) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-sm text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform transition hover:-translate-y-0.5">
                                        Generate Public Link
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Insights Card -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                            <h3 class="font-bold text-gray-900 dark:text-gray-100">AI Insights</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-6">
                                <li class="flex gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Sentence Variety
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Aim for
                                            a mix of short and long sentences to keep readers engaged.</p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div
                                            class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Target Keyword</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if($analysis->target_keyword)
                                                Optimizing for: <span
                                                    class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded font-mono text-gray-700 dark:text-gray-300">{{ $analysis->target_keyword }}</span>
                                            @else
                                                No keyword specified.
                                            @endif
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>