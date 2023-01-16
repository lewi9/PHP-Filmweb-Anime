<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
               <h2 style="font-family: 'Too Freakin Cute Demo', sans-serif; font-size: 30px; ">Back to main page ^•ﻌ•^ฅ♡ </h2>
            </a>
        </x-slot>
        <h2 class="h-font">Creating an Review</h2>
        <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
            @csrf
            @method("POST")

            <input type="text" id="user_id" name="user_id" value="{{Auth::id()}}" hidden>
            <input type="text" id="anime_id" name="anime_id" value="{{$anime->id}}" hidden>
            <!-- Title -->
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required/>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- text -->
            <div>
                <x-input-label for="text" :value="__('Text')" />
                <textarea id="text" class="block mt-1 w-full" type="text" name="text" :value="old('text')" required></textarea>
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

