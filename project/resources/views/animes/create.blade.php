<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>
        <h2>Creating an anime</h2>
        <form method="POST" action="{{ route('animes.store') }}">
            @csrf

            <!-- Title -->
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required/>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- genre -->
            <div>
                <x-input-label for="genre" :value="__('Genre')" />
                <x-text-input id="genre" class="block mt-1 w-full" type="text" name="genre" :value="old('genre')" required/>
                <x-input-error :messages="$errors->get('genre')" class="mt-2" />
            </div>

            <!-- production_year -->
            <div>
                <x-input-label for="production_year" :value="__('Production_year')" />
                <x-text-input id="production_year" class="block mt-1 w-full" type="number" name="production_year" :value="old('production_year')" required/>
                <x-input-error :messages="$errors->get('production_year')" class="mt-2" />
            </div>

            <!-- poster -->
            <div>
                <x-input-label for="poster" :value="__('Poster')" />
                <x-text-input id="poster" class="block mt-1 w-full" type="text" name="poster" :value="old('poster')"/>
                <x-input-error :messages="$errors->get('poster')" class="mt-2" />
            </div>

            <!-- description -->
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required/>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
