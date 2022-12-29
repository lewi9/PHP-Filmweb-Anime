<script>
    document.getElementById("back_anime_1").style.display='none';
    document.getElementById("back_anime_2").style.display='none';
</script>

<script>
    function edit_episodes()
    {
        document.getElementById("watched_episodes").readOnly = false;
    }
    function episodes(value)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('animes_users.episodes')}}",
            data: {
                'anime_id':{{$anime->id}},
                @if(Auth::id())
                'user_id': {{Auth::id() }},
                @endif
                'episodes': value,
            },
            success: function (data) {
                document.getElementById("watched_episodes").value = parseInt(data);
                document.getElementById("watched_episodes").readOnly = true;
                @if(!isset($anime_user->id) )
                if (data === "{{$anime->episodes}}") {
                    document.getElementById("rate0").checked = true;
                }

                @endif
            }
        });
    }

    function rate(value)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('animes_users.rate')}}",
            data: {
                'anime_id':{{$anime->id}},
                @if(Auth::id())
                'user_id': {{Auth::id() }},
                @endif
                'rating': value,
            },
            success: function (data) {
                document.getElementById("rate"+value).checked = true;
                document.getElementById("anime_rating").textContent = data.split(',')[0];
                document.getElementById("anime_rates").textContent = data.split(',')[1];
                document.getElementById("anime_crating").textContent = data.split(',')[2];
                document.getElementById("anime_hmuc").textContent = data.split(',')[3];
                @if(!isset($anime_user->id) )
                document.getElementById("watched_episodes").value = {{$anime->episodes}}
                    @endif
            }
        });
    }
    function favorite()
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('animes_users.manage_list')}}",
            data: {
                'anime_id':{{$anime->id}},
                @if(Auth::id())
                'user_id': {{Auth::id() }},
                @endif
                'list': 'favorite',
            },
            success: function (data) {
                if(data === "added"){
                    document.getElementById("favorite").textContent = "Remove from favorite";
                }
                else {
                    document.getElementById("favorite").textContent = "Add to fav animes";
                }
            }
        });
    }

    function to_watch()
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('animes_users.manage_list')}}",
            data: {
                'anime_id': {{ $anime->id }},
                @if(Auth::id())
                'user_id': {{Auth::id() }},
                @endif
                'list' : 'to_watch',
            },
            success: function (data) {
                if(data === "added"){
                    document.getElementById("to_watch").textContent = "Remove from to watch list";
                }
                else {
                    document.getElementById("to_watch").textContent = "Add to to watch list";
                }
            }
        });
    }
</script>
