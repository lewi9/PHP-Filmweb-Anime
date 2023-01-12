{{--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>--}}
{{--<script>--}}
{{--    function accept_invitation(id) {--}}
{{--        $.ajax({--}}
{{--            type: 'post',--}}
{{--            url: '{{URL::to('/invitations/accept')}}',--}}
{{--            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},--}}
{{--            data: {--}}
{{--                'inviting_user_id': id,--}}
{{--                @if(Auth::user())--}}
{{--                'user_id':{{Auth::user()->id}},--}}
{{--                @endif--}}
{{--            },--}}
{{--            success: function (data) {--}}

{{--            }--}}
{{--        });--}}
{{--    }--}}

{{--    function delete_invitation(id) {--}}
{{--        $.ajax({--}}
{{--            type: 'post',--}}
{{--            url: '{{URL::to('/invitations/delete')}}',--}}
{{--            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},--}}
{{--            data:{--}}
{{--                'inviting_user_id': id,--}}
{{--                @if(Auth::user())--}}
{{--                'user_id':{{Auth::user()->id}},--}}
{{--                @endif--}}
{{--            },--}}
{{--            success: function (data) {--}}

{{--            }--}}
{{--        });--}}
{{--    }--}}
{{--</script>--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($user->name . '\'s invitations') }}
            <img src="{{URL::asset('/images/' . $user->profile_pic)}}" alt="profile Pic" height="200" width="200">
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach($invitations_list as $inviting_user)
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <p>{{$message}}</p>
                    </div>
                @endif
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <x-nav-link :href="route('profile.show', $inviting_user->username)">
                        {{ __($inviting_user->name) }}
                    </x-nav-link>
                    <img src="{{URL::asset('/images/' . $inviting_user->profile_pic)}}" alt="profile Pic" height="200" width="200">
                    <x-nav-link :href="route('profile.invitations.accept', [$user->username, $inviting_user->username])">
                        {{__('Accept')}}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.invitations.delete', [$user->username, $inviting_user->username])">
                        {{__('Delete')}}
                    </x-nav-link>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
