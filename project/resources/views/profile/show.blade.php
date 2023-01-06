<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($user->name) }}
                <img src="{{URL::asset('/images/' . $user->profile_pic)}}" alt="profile Pic" height="200" width="200">
            @if($user->country)
                {{ __($user->country) }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (Auth::user()->id != $user->id)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <p>{{$message}}</p>
                        </div>
                    @endif
                    <x-nav-link :href="route('user.invite', $user->username)">
                        {{__('Add to friends')}}
                    </x-nav-link>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link :href="route('profile.watched', $user->username)">
                    {{ __('Watched animes') }}
                </x-nav-link>
            </div>

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
