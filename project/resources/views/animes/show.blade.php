<h2>Viewing an anime</h2>

<div>
    <img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
    <br>
    Title: <p id="anime_title">{{$anime->title}}</p>
    Genre:  <p id="anime_genre">{{$anime->genre}}</p>
    Production year:  <p id="anime_year">{{$anime->production_year}}</p>
    Poster path:  <p id="anime_poster">{{$anime->poster}}</p>
    Episodes: <p id="anime_episodes">{{$anime->episodes}}</p>
    Description: <p id="anime_description"> @markdown($anime->description)</p>
    Rating:  <p id="anime_rating">{{$anime->rating}}</p>
    HMUC:  <p id="anime_hmuc">{{$anime->how_much_users_watched}}</p>
    Rates:  <p id="anime_rates">{{$anime->rates}}</p>
    CRating: <p id="anime_crating">{{$anime->cumulate_rating}}</p>
</div>

@if(Auth::user())
    <div>
        <input onclick="rate(this.value);" type="radio" id="rate0" name="rate" value="0" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 0 and $anime_user->watched) checked @endif @endif/>
        <label for="rate0">Watched, none rate</label>
        <input onclick="rate(this.value);" type="radio" id="rate1" name="rate" value="1" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 1) checked @endif @endif />
        <label for="rate1">1</label>
        <input onclick="rate(this.value);" type="radio" id="rate2" name="rate" value="2" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 2) checked @endif @endif />
        <label for="rate2">2</label>
        <input onclick="rate(this.value);" type="radio" id="rate3" name="rate" value="3" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 3) checked @endif @endif/>
        <label for="rate3">3</label>
        <input onclick="rate(this.value);" type="radio" id="rate4" name="rate" value="4" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 4) checked @endif @endif/>
        <label for="rate4">4</label>
        <input onclick="rate(this.value);" type="radio" id="rate5" name="rate" value="5" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 5) checked @endif @endif/>
        <label for="rate5">5</label>
        <input onclick="rate(this.value);" type="radio" id="rate6" name="rate" value="6" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 6) checked @endif @endif/>
        <label for="rate6">6</label>
        <input onclick="rate(this.value);" type="radio" id="rate7" name="rate" value="7" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 7) checked @endif @endif/>
        <label for="rate7">7</label>
        <input onclick="rate(this.value);" type="radio" id="rate8" name="rate" value="8" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 8) checked @endif @endif/>
        <label for="rate8">8</label>
        <input onclick="rate(this.value);" type="radio" id="rate9" name="rate" value="9" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 9) checked @endif @endif/>
        <label for="rate9">9</label>
        <input onclick="rate(this.value);" type="radio" id="rate10" name="rate" value="10" @if(isset($anime_user->id)) @if(intval($anime_user->rating) == 10) checked @endif @endif/>
        <label for="rate10">10</label>
    </div>
    <div>
        <label for="watched_episodes">Watched episodes:</label>
        <button id="watched_episodes_save" onclick="episodes(document.getElementById('watched_episodes').value);">save</button>
        <input onclick="edit_episodes();" type="number" class="form-controller" id="watched_episodes" name="watched_episodes"
               value="{{$anime_user->watched_episodes ?? 0}}" readonly style="width:80px"> / {{$anime->episodes}}
        <button id="watched_episodes_+" onclick="episodes(parseInt(document.getElementById('watched_episodes').value)+1);">+</button>
    </div>
    @if(isset($anime_user->id))
        <div>
            @if($anime_user->favorite)
                <button id="favorite" onclick="favorite();">Remove from favorite</button>

            @else
            <button id="favorite" onclick="favorite();">Add to fav animes</button>
            @endif
            @if($anime_user->would_like_to_watch)
                <button id="to_watch" onclick="to_watch();">Remove from to watch list</button>
            @else
                <button id="to_watch" onclick="to_watch();">Add to to watch list</button>
            @endif
        </div>
    @else
        <div>
            <button id="favorite" onclick="favorite();">Add to fav animes</button>
            <button id="to_watch" onclick="to_watch();">Add to to watch list</button>
        </div>

    @endif


@endif
<div>
    <a href="{{ route('animes.edit', $anime)}}">Edit</a>
    <a href="{{ route('animes.delete', $anime)}}">Delete</a>
</div>

<div>
    <a href="{{ route('animes.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mt-8">All animes</a>
</div>

<div>
    <a href="{{route('comments.show', $anime)}}">All Comments</a>
</div>

@if(Auth::id())
    <form method="post" action={{route("comments.store")}}>
        @csrf
        @method('POST')
        <input id="user_id" name="user_id" type="hidden" value="{{Auth::id()}}">
        <input id="anime_id" name="anime_id" type="hidden" value="{{$anime->id}}">
        <p><label for="text">Add comment:</label></p>
        <textarea id="text" name="text" rows="4" cols="50">
        </textarea>
        <br>
        <input type="submit" value="Add comment">
    </form>
@endif

@include('animes.comments.show')

<script>
    document.getElementById("back_anime").style.display='none';
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
                document.getElementById("watched_episodes").value = data;
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
                    document.getElementById("anime_hmuc").textContent = data.split(',')[1];
                    document.getElementById("anime_rates").textContent = data.split(',')[2];
                    document.getElementById("anime_crating").textContent = data.split(',')[3];
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



