<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Analysis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('analyses.update', $analysis) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $analysis->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="content" :value="__('Content Preview (Read-only)')" />
                            <div
                                class="mt-1 block w-full p-2.5 bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md text-gray-500 dark:text-gray-400">
                                {{ Str::limit($analysis->content_raw, 200) }}
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Content cannot be edited to preserve analysis
                                integrity.</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Analysis') }}</x-primary-button>
                            <a href="{{ route('analyses.index') }}"
                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>