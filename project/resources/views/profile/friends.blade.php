<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight h-font" style="text-align: center;">
            {{ __($user->name . '\'s friends') }}
                <img class="center" style="width: 200px; height: 200px;" src="{{URL::asset('/images/' . $user->profile_pic)}}" alt="profile Pic" height="200" width="200">
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach($friends_list as $friend)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <x-nav-link style="font-size: 30px; color: #fa47c8;" :href="route('profile.show', $friend->username)">
                        {{ __($friend->name) }}
                    </x-nav-link>
                        <img src="{{URL::asset('/images/' . $friend->profile_pic)}}" alt="profile Pic" height="200" width="200">
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
