<x-app-layout>
<div id="hide_1">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-top: 15px;">
        <div class="bg-pink-200 overflow-hidden shadow-sm sm:rounded-lg selection-div" >
<h1>
    <a class="mini-button" id="back_anime_2" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">↞ Back to anime ↞</a>
</h1><br>
<div id="filter_form">
    <form>
        @csrf
        <label for="filter">Choose a filter type:</label>
        <select name="filter" id="filter" onchange="filter_select(this.value);">
            <option value="id" @if (session('reviews_filter') == "id") selected @endif>time</option>
            <option value="title" @if (session('reviews_filter') == "title") selected @endif>title</option>
            <option value="rating" @if (session('reviews_filter') == "rating") selected @endif>rating</option>
        </select>
        <label for="filter_mode">Choose a ascend or descend filter mode:</label>
        <select name="filter_mode" id="filter_mode" onchange="filter_mode_select(this.value);">
            <option value="asc" @if (session('comments_filter_mode') == "asc") selected @endif>ascending</option>
            <option value="desc" @if (session('comments_filter_mode') == "desc") selected @endif>descending</option>
        </select>
    </form>
    <br><button class="mini-button" onclick="reset();">Clear filters</button>
</div>
</div>
    </div></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" style="padding-top: 15px;">
        <div class="p-1 sm:p-8 bg-white shadow sm:rounded-lg">
<div id="reviews">
    @if($reviews instanceof Illuminate\Http\Response)
            <?php echo $reviews->content() ?>
    @else
        @if(count($reviews)==0)
            There is no reviews
        @else
            @foreach($reviews as $review)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg pink-shadow">
                <div id="{{$review->id . 'div'}}">
                    <p><strong>{{$review->name}}</strong></p>
                    <p>{{$review->title}}</p>
                    <p>Review rating: {{$review->rating}}</p>
                    <a class="mini-button" href="{{route('reviews.show', [$anime->title, $anime->production_year, $anime->id, $review->id])}}">Read review</a>
                    @if(Auth::id() == $review->user_id)
                        <a class="mini-button" style="float:right" href="{{route('reviews.edit', [$anime->title, $anime->production_year, $anime->id, $review->id])}}">Edit review</a>
                        <form id="delete_review" action="{{route('reviews.delete',[$anime->title, $anime->production_year, $anime->id, $review->id])}}" method="post">
                            @csrf
                            @method('DELETE')
                            <a class="mini-button" style="float:right; margin-top: 5px;"  href="javascript:{}" onclick="document.getElementById('delete_review').submit(); return false;">Delete review</a>
                        </form>
                    @endif
                    <br>
                </div></div><br>
            @endforeach

        @endif
    @endif
        <a class="mini-button" href="{{route('reviews.create', [$anime->title, $anime->production_year, $anime->id])}}">Create review</a>
</div></div></div>
@include('animes.reviews.indexjs')
</x-app-layout>
