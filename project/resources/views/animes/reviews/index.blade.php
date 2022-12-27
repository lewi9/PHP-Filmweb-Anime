<h1>
    <a id="back_anime_2" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>

@if(count($reviews)==0)
    There is no reviews
@else
    @foreach($reviews as $review)

        <div id="{{$review->id . 'div'}}">
            <p><strong>{{$review->name}}</strong></p>
            <p>{{$review->title}}</p>
            <p>Review rating: {{$review->rating}}</p>
            <a href="{{route('reviews.show', [$anime->title, $anime->production_year, $anime->id, $review->id])}}">Read review</a>
            @if(Auth::id() == $review->user_id)
                <a href="{{route('reviews.edit', [$anime->title, $anime->production_year, $anime->id, $review->id])}}">Edit review</a>
                <form id="delete_review" action="{{route('reviews.delete',[$anime->title, $anime->production_year, $anime->id, $review->id])}}" method="post">
                    @csrf
                    @method('DELETE')
                    <a href="javascript:{}" onclick="document.getElementById('delete_review').submit(); return false;">Delete review</a>
                </form>
            @endif
            <br>
        </div>
    @endforeach

@endif
<br><br>
<a href="{{route('reviews.create', [$anime->title, $anime->production_year, $anime->id])}}">Create review</a>

