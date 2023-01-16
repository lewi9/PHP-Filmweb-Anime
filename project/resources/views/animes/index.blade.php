<x-app-layout>
<h2 class="h-font">♡ List of Animes ♡</h2><br>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-pink-200 overflow-hidden shadow-sm sm:rounded-lg selection-div">
    <div id="filter_form">
    <form>
        @csrf
        <label class="select" for="filter">Choose a filter type:</label>
        <select name="filter" id="filter" onchange="filter_select(this.value);">
            <option value="id" @if (session('anime_filter') == "id") selected @endif>default</option>
            <option value="title" @if (session('anime_filter') == "title") selected @endif>title</option>
            <option value="production_year" @if (session('anime_filter') == "production_year") selected @endif>production year</option>
            <option value="rating" @if (session('anime_filter') == "rating") selected @endif>rating</option>
            <option value="how_much_users_watched" @if (session('anime_filter') == "how_much_users_watched") selected @endif>watches</option>
        </select>
        <label for="filter_mode"> </label>
        <select name="filter_mode" id="filter_mode" onchange="filter_mode_select(this.value);">
            <option value="asc" @if (session('anime_filter_mode') == "asc") selected @endif>ascending</option>
            <option value="desc" @if (session('anime_filter_mode') == "desc") selected @endif>descending</option>
        </select>
        <br>
        <label for="filter_genre">Choose a genre to filter</label>
        <select name="filter_genre" id="filter_genre" onchange="filter_genre_select(this.value);">
            <option value="all" @if (session('anime_filter_genre') == "all") selected @endif>all</option>
            @if (isset($genres))
            @foreach($genres as $genre)
                <option value="{{$genre}}" @if (session('anime_filter_genre') == $genre ) selected @endif>{{$genre}}</option>
            @endforeach
            @endif
        </select>
        <br><br>
        <label for="filter_search">Type text to search anime by title:</label>
        <input type="text" class="form-controller" id="filter_search" name="filter_search"
               value="{{  (session('anime_filter_search') ?? "") == '%' ? '' :  (session('anime_filter_search') ?? "") }}">
    </form><br><br>
    <button class="button" onclick="reset();">Clear filters</button>
</div>
    </div></div>
    <br>
    <div class="max-w-7xl mx-auto sm:px-3 lg:px-8 space-y-6 anime-width">
    <div id="anime">
    <?php
        if (isset($animes)) {
            echo $animes->content();
        }
    ?>
</div>

<br><br>
    @if(Auth::user() and Auth::user()->is_admin)
<a href="{{route('animes.create')}}">Create new...</a>;
@endif
@include('animes.indexjs')
</div>
</x-app-layout>
