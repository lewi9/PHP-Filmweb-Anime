<x-app-layout>

    <br>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-1 sm:p-8 bg-white shadow sm:rounded-lg">
        <h2 class="h-font">{{$anime->title}}</h2>
<div class="anime-info">
    <img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
    <br>
    Title: <p class="anime-details" id="anime_title">{{$anime->title}}</p>
    Genre:  <p class="anime-details" id="anime_genre">{{$anime->genre}}</p>
    Production year:  <p class="anime-details" id="anime_year">{{$anime->production_year}}</p>
    Poster path:  <p class="anime-details" id="anime_poster">{{$anime->poster}}</p>
    Episodes: <p class="anime-details" id="anime_episodes">{{$anime->episodes}}</p>
    Description: <p class="anime-details" id="anime_description"> @markdown($anime->description)</p>
    Rating:  <p class="anime-details" id="anime_rating">{{$anime->rating}}</p>
    HMUC:  <p class="anime-details" id="anime_hmuc">{{$anime->how_much_users_watched}}</p>
    Rates:  <p class="anime-details" id="anime_rates">{{$anime->rates}}</p>
    CRating: <p class="anime-details" id="anime_crating">{{$anime->cumulate_rating}}</p>
</div>

@if(Auth::user())
    <div  id="like" class="rating">
        <input onclick="rate(this.value);" type="radio" id="rate0" name="rate" value="0" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 0 and $anime_user->watched) checked @endif @endif/>
        <label for="rate0">Watched, none rate</label><br>
        <input onclick="rate(this.value);" type="radio" id="rate1" name="rate" value="1" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 1) checked @endif @endif />
        <label for="rate1">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate2" name="rate" value="2" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 2) checked @endif @endif />
        <label for="rate2">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate3" name="rate" value="3" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 3) checked @endif @endif/>
        <label for="rate3">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate4" name="rate" value="4" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 4) checked @endif @endif/>
        <label for="rate4">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate5" name="rate" value="5" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 5) checked @endif @endif/>
        <label for="rate5">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate6" name="rate" value="6" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 6) checked @endif @endif/>
        <label for="rate6">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate7" name="rate" value="7" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 7) checked @endif @endif/>
        <label for="rate7">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate8" name="rate" value="8" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 8) checked @endif @endif/>
        <label for="rate8">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate9" name="rate" value="9" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 9) checked @endif @endif/>
        <label for="rate9">❤︎</label>
        <input onclick="rate(this.value);" type="radio" id="rate10" name="rate" value="10" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 10) checked @endif @endif/>
        <label for="rate10">❤︎</label>
    </div><br>
    <div>
        <label for="watched_episodes">Watched episodes:</label>
        <button class="mini-button" id="watched_episodes_save" onclick="episodes(document.getElementById('watched_episodes').value);">save</button>
        <input onclick="edit_episodes();" type="number" class="form-controller" id="watched_episodes" name="watched_episodes"
               value="{{$anime_user->watched_episodes ?? 0}}" readonly style="width:80px"> / {{$anime->episodes}}
        <button class="mini-button" id="watched_episodes_+" onclick="episodes(parseInt(document.getElementById('watched_episodes').value)+1);">+</button>
    </div>
    @if(isset($anime_user->id))
        <div>
            @if($anime_user->favorite)
                <br><button class="mini-button" id="favorite" onclick="favorite();">Remove from favorite</button>

            @else
            <button class="mini-button" id="favorite" onclick="favorite();">Add to fav animes</button>
            @endif
            @if($anime_user->would_like_to_watch)
                <button class="mini-button" id="to_watch" onclick="to_watch();">Remove from to watch list</button>
            @else
                <button class="mini-button" id="to_watch" onclick="to_watch();">Add to to watch list</button>
            @endif
        </div><br>
    @else
        <div>
            <button class="mini-button" id="favorite" onclick="favorite();">Add to fav animes</button>
            <button class="mini-button" id="to_watch" onclick="to_watch();">Add to to watch list</button>
        </div>

    @endif


@endif
@if(Auth::user() and Auth::user()->is_admin)
<div>
    <a class="mini-button" href="{{ route('animes.edit', $anime)}}">Edit</a>
    <a class="mini-button" href="{{ route('animes.delete', $anime)}}">Delete</a>
</div>
@endif


<div>
    <a class="mini-button" href="{{ route('animes.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mt-8">All animes</a>
    <a class="mini-button" href="{{route('comments.show', [$anime->title, $anime->production_year, $anime->id])}}">All Comments</a>
    <a class="mini-button" href="{{route('reviews.index', [$anime->title, $anime->production_year, $anime->id])}}">All reviews</a><br>
</div>

<div>

    <h4 class="h-font">Reviews</h4>

    @include('animes.reviews.index')


</div>
    </div>
    </div>
    <br>
@if(Auth::id())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-1 sm:p-8 bg-white shadow sm:rounded-lg">
    <div id="add_comment">
        <form method="post" action={{route("comments.store")}}>
            @csrf
            @method('POST')
            <input id="user_id" name="user_id" type="hidden" value="{{Auth::id()}}">
            <input id="anime_id" name="anime_id" type="hidden" value="{{$anime->id}}">
            <p><label class="font1" for="text">Add your comment:</label></p>
            <textarea  id="text" name="text" rows="5" cols="60" > </textarea>
            <br>
            <input class="mini-button" type="submit" value="Add comment">
        </form>
    </div></div></div>
@endif

@include('animes.comments.show')

@include('animes.showjs')



</x-app-layout>
