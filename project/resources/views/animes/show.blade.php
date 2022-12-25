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

@if(count($comments)==0)
    There is no comments
@else
    @foreach($comments as $comment)

        <br>
        <label for="{{$comment->id . "_"}}">{{$comment->name}}</label>
        <form method="POST" action="{{route('comments.update')}}">
            @csrf
            @method("PATCH")
            <input type="hidden" name="c_id" value="{{$comment->id}}">
            <input id="title" name="title" type="hidden" value="{{$anime->title}}">
            <input id="production_year" name="production_year" type="hidden" value="{{$anime->production_year}}">
            <input id="id" name="id" type="hidden" value="{{$anime->id}}">
            <textarea id="{{$comment->id . "_"}}" name="text" rows="4" cols="50" disabled>
            {{$comment->text}}
            </textarea><br>
            <button id="{{$comment->id . "__"}}" type="submit" style="visibility: hidden">Update!</button>
        </form>
        @if(Auth::user())
            @if(Auth::user()->id == $comment->author_id)
                <button id="{{$comment->id}}" onclick="action(this.id);">Edit Comment</button>
                <form method="post" action="{{route('comments.delete')}}">
                    <input name="c_id" id="c_id" type="hidden" value="{{$comment->id}}">
                    <input id="title" name="title" type="hidden" value="{{$anime->title}}">
                    <input id="production_year" name="production_year" type="hidden" value="{{$anime->production_year}}">
                    <input id="id" name="id" type="hidden" value="{{$anime->id}}">
                    @method('DELETE')
                    @csrf
                    <button type="submit">Delete Comment</button>
                </form>
            @endif
        @endif

    @endforeach
@endif

<script>
    function action(id)
    {
        document.getElementById(id + "_").disabled = false;
        document.getElementById(id + "__").style.visibility = 'visible';
    }
</script>




