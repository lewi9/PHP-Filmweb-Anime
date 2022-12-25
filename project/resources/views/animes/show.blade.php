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

<a href="{{ route('animes.edit', $anime)}}">Edit</a>
<a href="{{ route('animes.delete', $anime)}}">Delete</a>

<a href="{{ route('animes.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mt-8">All animes</a>

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

<script> document.getElementById("back_anime").style.display='none'</script>



