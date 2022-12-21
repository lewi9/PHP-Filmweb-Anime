<h2>List of Animes</h2>
@if (count($animes) === 0)
    No animes in database.
@else
    @foreach($animes as $anime)
        @markdown($anime->title)
        <a href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Details</a>
  @endforeach
@endif
<br><br>
<a href="{{route('animes.create')}}">Create new...</a>;

