<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ratings') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <ol>
            @foreach($animes as $anime)
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <li>
                    <x-nav-link :href="route('animes.show', [$anime->title, $anime->production_year, $anime->id])">
                        {{ __($anime->title) }}
                    </x-nav-link>
                        </li>
                </div>
            @endforeach
            </ol>
        </div>
    </div>
</x-app-layout>
