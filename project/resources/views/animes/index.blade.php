<h2>List of Animes</h2>
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

