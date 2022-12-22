<h2>Viewing an anime</h2>
<img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
@markdown($anime->title)
@markdown($anime->genre)
@markdown($anime->production_year)
@markdown($anime->poster)
@markdown($anime->description)
@php
    if( isset($anime) ) {
            if( !$anime->how_much_users_watched ) echo "NaN";
            else echo $anime->rating/$anime->how_much_users_watched;
    }
@endphp
@markdown($anime->how_much_users_watched)

<a href="{{ route('animes.edit', $anime)}}">Edit</a>
<a href="{{ route('animes.delete', $anime)}}">Delete</a>

<a href="{{ route('animes.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mt-8">All animes</a>
