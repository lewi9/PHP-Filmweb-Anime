<h2>List of Animes</h2>
<form>
    @csrf
    <label for="filter">Choose a filter type:</label>
    <select name="filter" id="filter" onchange="filter_select(this.value);">
        <option value="id" @if (session('anime_filter') == "id") selected @endif>id</option>
        <option value="title" @if (session('anime_filter') == "title") selected @endif>title</option>
        <option value="production_year" @if (session('anime_filter') == "production_year") selected @endif>production year</option>
        <option value="rating" @if (session('anime_filter') == "rating") selected @endif>rating</option>
        <option value="how_much_users_watched" @if (session('anime_filter') == "how_much_users_watched") selected @endif>watchs</option>
    </select>
    <label for="filter_mode">Choose a ascend or descend filter mode:</label>
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
    <br>
    <label for="filter_search">Type text to search anime by title:</label>
    <input type="text" class="form-controller" id="filter_search" name="filter_search">
</form>
<button onclick="reset();">Clear filters</button>
<div id="anime">

    <?php
        if (isset($animes)) {
            echo $animes->content();
        }
    ?>
</div>

<br><br>
<a href="{{route('animes.create')}}">Create new...</a>;


<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
    $('#filter_search').on('keyup',function(){
        let value = $(this).val();
        $.ajax({
            type : 'get',
            url : '{{URL::to('/anime/filter')}}',
            data:{'filter_search':value},
            success:function(data){
                $('#anime').html(data);
            }
        });
    })
</script>

<script type="text/javascript">
    function filter_select(val)
    {
        $.ajax({
            type: 'get',
            url: '{{URL::to('/anime/filter')}}',
            data: {
                'filter':val
            },
            success: function (data) {
                $('#anime').html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function filter_mode_select(val)
    {
        $.ajax({
            type: 'get',
            url: '{{URL::to('/anime/filter')}}',
            data: {
                'filter_mode':val
            },
            success: function (data) {
                $('#anime').html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function filter_genre_select(val)
    {
        $.ajax({
            type: 'get',
            url: '{{URL::to('/anime/filter')}}',
            data: {
                'filter_genre':val
            },
            success: function (data) {
                $('#anime').html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function reset()
    {
        $.ajax({
            type: 'get',
            url: '{{URL::to('/anime/filter')}}',
            data: {
                'filter' : 'id',
                'filter_mode' : 'asc',
                'filter_genre': 'all',
                'filter_search':'%',

            },
            success: function (data) {
                $('#anime').html(data);
            }
        });
    }
</script>
