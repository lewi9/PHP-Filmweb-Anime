<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
<h1>
    <a class="mini-button" style="display: block; margin-left: auto; margin-right: auto; margin-top: 10px;" id="back_anime_2" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">↞ Back to anime ↞</a>
</h1>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg pink-shadow">
<div id="{{$review->id . 'div'}}">
    <p><strong>{{$review->name}}</strong></p>
    <p class="h-font">{{$review->title}}</p>
    <p>{{$review->text}}</p>
    <a class="anime-info">Review rating: </a><a id="review_rating">{{$review->rating}}</a><br>
    <a class="anime-info">Review rates: </a><a id="review_rates">{{$review->rates}}</a><br>
    @if(Auth::id() == $review->user_id)
        <a class="mini-button" href="{{route('reviews.edit', [$anime->title, $anime->production_year, $anime->id, $review->id])}}">Edit review</a>
        <form id="delete_review" action="{{route('reviews.delete',[$anime->title, $anime->production_year, $anime->id, $review->id])}}" method="post">
            @csrf
            @method('DELETE')
            <a class="mini-button" style="margin-top: 5px;" href="javascript:{}" onclick="document.getElementById('delete_review').submit(); return false;">Delete review</a>
        </form><br></div>
    @endif
    @if(Auth::user())
        <div>
            <a>Rate this anime:</a>
            <section id="like" class="rating">
                <!-- FIRST HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_1" name="like" value="1" @if(isset($review_user->id)) @if(intval($review_user->rating) == 1) checked @endif @endif/>
                <label for="heart_1" title="One">&#10084;</label>
                <!-- SECOND HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_2" name="like" value="2" @if(isset($review_user->id)) @if(intval($review_user->rating) == 2) checked @endif @endif/>
                <label for="heart_2" title="Two">&#10084;</label>
                <!-- THIRD HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_3" name="like" value="3" @if(isset($review_user->id)) @if(intval($review_user->rating) == 3) checked @endif @endif/>
                <label for="heart_3" title="Three">&#10084;</label>
                <!-- FOURTH HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_4" name="like" value="4" @if(isset($review_user->id)) @if(intval($review_user->rating) == 4) checked @endif @endif/>
                <label for="heart_4" title="Four">&#10084;</label>
                <!-- FIFTH HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_5" name="like" value="5" @if(isset($review_user->id)) @if(intval($review_user->rating) == 5) checked @endif @endif />
                <label for="heart_5" title="Five">&#10084;</label>
                <!-- six HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_6" name="like" value="6" @if(isset($review_user->id)) @if(intval($review_user->rating) == 6) checked @endif @endif/>
                <label for="heart_6" title="Six">&#10084;</label>
                <!-- seven HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_7" name="like" value="7" @if(isset($review_user->id)) @if(intval($review_user->rating) == 7) checked @endif @endif/>
                <label for="heart_7" title="Seven">&#10084;</label>
                <!-- 8 HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_8" name="like" value="8" @if(isset($review_user->id)) @if(intval($review_user->rating) == 8) checked @endif @endif/>
                <label for="heart_8" title="Eight">&#10084;</label>
                <!-- 9 HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_9" name="like" value="9" @if(isset($review_user->id)) @if(intval($review_user->rating) == 9) checked @endif @endif/>
                <label for="heart_9" title="Nine">&#10084;</label>
                <!-- 10 HEART -->
                <input onclick="rate(this.value);" type="radio" id="heart_10" name="like" value="10" @if(isset($review_user->id)) @if(intval($review_user->rating) == 10) checked @endif @endif />
                <label for="heart_10" title="Ten">&#10084;</label>



            </section>
{{--            <input onclick="rate(this.value);" type="radio" id="rate0" name="rate" value="0" @if(isset($review_user->id)) @if(intval($review_user->rating) == 0) checked @endif @endif--}}
{{--                    @if(!isset($review_user->id)) checked @endif/>--}}
{{--            <label for="rate0">None rate</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate1" name="rate" value="1" @if(isset($review_user->id)) @if(intval($review_user->rating) == 1) checked @endif @endif />--}}
{{--            <label for="rate1">1</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate2" name="rate" value="2" @if(isset($review_user->id)) @if(intval($review_user->rating) == 2) checked @endif @endif />--}}
{{--            <label for="rate2">2</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate3" name="rate" value="3" @if(isset($review_user->id)) @if(intval($review_user->rating) == 3) checked @endif @endif/>--}}
{{--            <label for="rate3">3</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate4" name="rate" value="4" @if(isset($review_user->id)) @if(intval($review_user->rating) == 4) checked @endif @endif/>--}}
{{--            <label for="rate4">4</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate5" name="rate" value="5" @if(isset($review_user->id)) @if(intval($review_user->rating) == 5) checked @endif @endif/>--}}
{{--            <label for="rate5">5</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate6" name="rate" value="6" @if(isset($review_user->id)) @if(intval($review_user->rating) == 6) checked @endif @endif/>--}}
{{--            <label for="rate6">6</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate7" name="rate" value="7" @if(isset($review_user->id)) @if(intval($review_user->rating) == 7) checked @endif @endif/>--}}
{{--            <label for="rate7">7</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate8" name="rate" value="8" @if(isset($review_user->id)) @if(intval($review_user->rating) == 8) checked @endif @endif/>--}}
{{--            <label for="rate8">8</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate9" name="rate" value="9" @if(isset($review_user->id)) @if(intval($review_user->rating) == 9) checked @endif @endif/>--}}
{{--            <label for="rate9">9</label>--}}
{{--            <input onclick="rate(this.value);" type="radio" id="rate10" name="rate" value="10" @if(isset($review_user->id)) @if(intval($review_user->rating) == 10) checked @endif @endif/>--}}
{{--            <label for="rate10">10</label>--}}
        </div>
    @endif
    <br>
</div>
<h1>
    <a class="mini-button" style="margin-bottom: 10px" href="{{route('reviews.index', [$anime->title, $anime->production_year, $anime->id])}}">All reviews</a>
</h1>

@include('animes.reviews.showjs')
            </div></div></div>
</x-app-layout>
