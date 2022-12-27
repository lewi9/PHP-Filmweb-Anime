<h1>
    <a id="back_anime_2" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>

@if(count($reviews)==0)
    There is no reviews
@else
    @foreach($reviews as $review)

        <div id="{{$review->id . 'div'}}">
            <strong><p>{{$review->name}}</p></strong>
            <p>{{$review->title}}</p>
            <br>
        </div>
    @endforeach
@endif
