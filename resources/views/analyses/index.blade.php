<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Analyses') }}
            </h2>
            <a href="{{ route('analyses.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-600 hover:to-purple-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                {{ __('New Analysis') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Total
                        Analyses</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalAnalyses }}</div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Total
                        Words Scanned</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalWords) }}
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            @if($analyses->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                        You haven't created any text analyses yet.
                    </div>
                    <a href="{{ route('analyses.create') }}"
                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                        Get started by creating one &rarr;
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($analyses as $analysis)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl hover:shadow-md transition-shadow duration-300 border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate"
                                        title="{{ $analysis->title }}">
                                        {{ $analysis->title ?: 'Untitled Analysis' }}
                                    </h3>
                                    
                                    <form action="{{ route('analyses.destroy', $analysis) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <span
                                        class="absolute top-0 right-0 mt-6 mr-6 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hidden">
                                        {{ $analysis->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-3">
                                    {{ Str::limit($analysis->content_raw, 100) }}
                                </p>

                                <div
                                    class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
                                    <div class="flex gap-4">
                                        <span>{{ $analysis->word_count }} words</span>
                                        <span>Readability: {{ $analysis->readability_score }}</span>
                                    </div>
                                    <div class="flex gap-4">
                                        <a href="{{ route('analyses.edit', $analysis) }}"
                                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            Edit
                                        </a>
                                        <a href="{{ route('analyses.show', $analysis) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            View &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $analyses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>