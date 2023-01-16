<?php
    if (isset($user)) {
        $friendship_status1 = \App\Models\UsersFriends::where('user1_id', Auth::user()->id)->where('user2_id', $user->id)->first();
        $friendship_status2 = \App\Models\UsersFriends::where('user1_id', $user->id)->where('user2_id', Auth::user()->id)->first();
        if ($friendship_status1) {
            $friendship_status = $friendship_status1;
        }
        if ($friendship_status2) {
            $friendship_status = $friendship_status2;
        }
    }
    ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight h-font" style="text-align: center;">
            {{ __($user->name) }}
                <img class="center" style="width: 200px; height: 200px;" src="{{URL::asset('/images/' . $user->profile_pic)}}" alt="profile Pic" height="200" width="200">
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
                    @if(!isset($friendship_status))
                        <x-nav-link id="add_to_friends" :href="route('user.invite', $user->username)">
                            {{__('Add to friends')}}
                        </x-nav-link>
                    @elseif($friendship_status->is_pending)
                            {{__('Invitation is pending')}}
                    @else
                            <x-nav-link id="delete_from_friends" :href="route('profile.friendship.delete', $user->username)">
                                {{__('Delete from friends')}}
                            </x-nav-link>
                    @endif
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id="watched_animes" :href="route('profile.watched', $user->username)">
                    {{ __('Watched animes') }}
                </x-nav-link>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id='ratings' :href="route('profile.ratings', $user->username)">
                    {{ __('Ratings') }}
                </x-nav-link>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id='favorites' :href="route('profile.favorites', $user->username)">
                    {{ __('Favorites') }}
                </x-nav-link>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id="wants_to_see" :href="route('profile.to-watch', $user->username)">
                    {{__('Wants to see')}}
                </x-nav-link>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id="friends" :href="route('profile.friends', $user->username)">
                    {{__('Friends')}}
                </x-nav-link>
            </div>
            @if (Auth::user()->id == $user->id)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id="friends_invitations" :href="route('profile.invitations', $user->username)">
                    {{__('Friends invitations')}}
                </x-nav-link>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-nav-link id="edit_profile" :href="route('profile.edit', $user->username)">
                    {{__('Edit profile')}}
                </x-nav-link>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
