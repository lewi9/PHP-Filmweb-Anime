<h2>Viewing an anime</h2>
<img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
@markdown($anime->title)
@markdown($anime->genre)
@markdown($anime->production_year)
@markdown($anime->poster)
@markdown($anime->description)
@php
    if( isset($anime) ) {
            if( !$anime->rates ) echo "NaN";
            else echo $anime->cumulate_rating/$anime->rates;
    }
@endphp
@markdown($anime->how_much_users_watched)

@if(Auth::user())
    @if(isset($anime_user->id))
        @if($anime_user->favorite)
            <a id="favorite" href="#" onclick="favorite();">Remove from favorite</a>

        @else
        <a id="favorite" href="#" onclick="favorite();">Add to fav animes</a>
        @endif
        @if($anime_user->would_like_to_watch)
            <a id="to_watch" href="#" onclick="to_watch();">Remove from to watch list</a>
        @else
            <a id="to_watch" href="#" onclick="to_watch();">Add to to watch list</a>
        @endif

    @else
        <a id="favorite" href="#" onclick="favorite();">Add to fav animes</a>
        <a id="to_watch" href="#" onclick="to_watch();">Add to to watch list</a>
    @endif

@endif
<br>
<a href="{{ route('animes.edit', $anime)}}">Edit</a>
<a href="{{ route('animes.delete', $anime)}}">Delete</a>

<br>
<a href="{{ route('animes.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mt-8">All animes</a>

<br>
<a href="{{route('comments.show', $anime)}}">All Comments</a>

@if(Auth::user())
@if(Auth::user()->id)
    <form method="post" action={{route("comments.store")}}>
        @csrf
        @method('POST')
        <input id="user_id" name="user_id" type="hidden" value="{{Auth::user()->id}}">
        <input id="anime_id" name="anime_id" type="hidden" value="{{$anime->id}}">
        <input id="title" name="title" type="hidden" value="{{$anime->title}}">
        <input id="production_year" name="production_year" type="hidden" value="{{$anime->production_year}}">
        <p><label for="text">Add comment:</label></p>
        <textarea id="text" name="text" rows="4" cols="50">
        </textarea>
        <br>
        <input type="submit" value="Add comment">
    </form>
@endif
@endif

@include('animes.comments.show')

<script>
    document.getElementById("back_anime").style.display='none';
</script>

<script>
    function favorite()
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('animes_users.favorite')}}",
            data: {
                'anime_id':{{$anime->id}},
                'user_id': @if(Auth::user()) {{Auth::user()->id }},@else 0 @endif
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
            url: "{{route('animes_users.to_watch')}}",
            data: {
                'anime_id': {{ $anime->id }},
                'user_id': @if( Auth::user() ) {{ Auth::user()->id }}, @else 0 @endif
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



