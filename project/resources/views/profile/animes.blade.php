<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($user->name) }}
            @if($user->profile_pic=='-1')
                <img src="{{URL::asset('/images/missing.jpg')}}" alt="profile Pic" height="200" width="200">
            @else
                <img src="{{URL::asset('/images/' . $user->profile_pic)}}" alt="profile Pic" height="200" width="200">
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach($anime_list as $anime)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @if ($type == 'ratings')
                        {{ __($anime[0]->title) }}
                        {{ __($anime[1]) }}
                    @else
                        {{ __($anime->title) }}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
