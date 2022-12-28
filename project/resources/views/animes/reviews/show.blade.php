<h1>
    <a id="back_anime_2" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>

<div id="{{$review->id . 'div'}}">
    <p><strong>{{$review->name}}</strong></p>
    <p>{{$review->title}}</p>
    <p>{{$review->text}}</p>
    Review rating: <p id="review_rating">{{$review->rating}}</p>
    Review rates: <p id="review_rates">{{$review->rates}}</p>
    @if(Auth::user())
        <div>
            <input onclick="rate(this.value);" type="radio" id="rate0" name="rate" value="0" @if(isset($review_user->id)) @if(intval($review_user->rating) == 0) checked @endif @endif
                    @if(!isset($review_user->id)) checked @endif/>
            <label for="rate0">None rate</label>
            <input onclick="rate(this.value);" type="radio" id="rate1" name="rate" value="1" @if(isset($review_user->id)) @if(intval($review_user->rating) == 1) checked @endif @endif />
            <label for="rate1">1</label>
            <input onclick="rate(this.value);" type="radio" id="rate2" name="rate" value="2" @if(isset($review_user->id)) @if(intval($review_user->rating) == 2) checked @endif @endif />
            <label for="rate2">2</label>
            <input onclick="rate(this.value);" type="radio" id="rate3" name="rate" value="3" @if(isset($review_user->id)) @if(intval($review_user->rating) == 3) checked @endif @endif/>
            <label for="rate3">3</label>
            <input onclick="rate(this.value);" type="radio" id="rate4" name="rate" value="4" @if(isset($review_user->id)) @if(intval($review_user->rating) == 4) checked @endif @endif/>
            <label for="rate4">4</label>
            <input onclick="rate(this.value);" type="radio" id="rate5" name="rate" value="5" @if(isset($review_user->id)) @if(intval($review_user->rating) == 5) checked @endif @endif/>
            <label for="rate5">5</label>
            <input onclick="rate(this.value);" type="radio" id="rate6" name="rate" value="6" @if(isset($review_user->id)) @if(intval($review_user->rating) == 6) checked @endif @endif/>
            <label for="rate6">6</label>
            <input onclick="rate(this.value);" type="radio" id="rate7" name="rate" value="7" @if(isset($review_user->id)) @if(intval($review_user->rating) == 7) checked @endif @endif/>
            <label for="rate7">7</label>
            <input onclick="rate(this.value);" type="radio" id="rate8" name="rate" value="8" @if(isset($review_user->id)) @if(intval($review_user->rating) == 8) checked @endif @endif/>
            <label for="rate8">8</label>
            <input onclick="rate(this.value);" type="radio" id="rate9" name="rate" value="9" @if(isset($review_user->id)) @if(intval($review_user->rating) == 9) checked @endif @endif/>
            <label for="rate9">9</label>
            <input onclick="rate(this.value);" type="radio" id="rate10" name="rate" value="10" @if(isset($review_user->id)) @if(intval($review_user->rating) == 10) checked @endif @endif/>
            <label for="rate10">10</label>
        </div>
    @endif
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
<h1>
    <a href="{{route('reviews.index', [$anime->title, $anime->production_year, $anime->id])}}">All reviews</a>
</h1>

@include('animes.reviews.showjs')
