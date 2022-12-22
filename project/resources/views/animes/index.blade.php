<h2>List of Animes</h2>
<form method = "get" action="{{route("animes.index")}}">
    <label for="filter">Choose a filter type:</label>
    <select name="filter" id="filter">
        <option value="id" @if (session('filter') == "id") selected @endif>id</option>
        <option value="title" @if (session('filter') == "title") selected @endif>title</option>
        <option value="production_year" @if (session('filter') == "production_year") selected @endif>production year</option>
        <option value="rating" @if (session('filter') == "rating") selected @endif>rating</option>
        <option value="how_much_users_watched" @if (session('filter') == "how_much_users_watched") selected @endif>watchs</option>
    </select>
    <input type="submit">
</form>
@if (count($animes) === 0)
    No animes in database.
@else
    @foreach($animes as $anime)
        <img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
        @markdown($anime->title)
        <a href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Details</a>
        <br>
  @endforeach
@endif
<br><br>
<a href="{{route('animes.create')}}">Create new...</a>;

