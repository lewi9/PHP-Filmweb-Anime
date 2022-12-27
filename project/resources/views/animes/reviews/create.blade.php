<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>
        <h2>Creating an anime</h2>
        <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" id="user_id" name="user_id" value="{{Auth::id()}}" hidden>
            <input type="text" id="anime_id" name="anime_id" value="{{$anime->id}}" hidden>
            <!-- Title -->
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required/>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- description -->
            <div>
                <x-input-label for="text" :value="__('Text')" />
                <input id="text" type="text" name="text" :value="old('text')" style="width:200px;height:100px"/>
                <x-input-error :messages="$errors->get('text')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

