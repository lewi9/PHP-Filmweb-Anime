<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($user->name) }}
            <img src="{{URL::asset('/images/sailor.jpg')}}" alt="profile Pic" height="200" width="200">
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.ratings', $user->username)">
                    {{ __('Ratings') }}
                </x-nav-link>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.favorites', $user->username)">
                    {{ __('Favorites') }}
                </x-nav-link>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.to-watch', $user->username)">
                    {{__('Wants to see')}}
                </x-nav-link>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.friends', $user->username)">
                    {{__('Friends')}}
                </x-nav-link>
            </div>
            @if (Auth::user()->id == $user->id)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.edit', $user->username)">
                    {{__('Edit profile')}}
                </x-nav-link>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
