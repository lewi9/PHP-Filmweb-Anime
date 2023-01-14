<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    $(function () {

        $('form').on('submit', function (e) {
            var year = document.getElementById("production_year").value;
            var genre = document.getElementById("genre").value;
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{URL::to('/ratings/calculate')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'genre' : genre,
                    'production_year' : year,
                },
                success: function (data) {
                    $('#anime').html(data);
                }
            });
        });
    });
</script>

<?php
$production_years =   \App\Models\Anime::select('production_year')->distinct()->get()->toArray();
$genres = \App\Models\Anime::select('genre')->distinct()->get()->toArray();
$y = 'all';
$g = 'all';
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ratings') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route("ratings.calculate") }}" enctype="multipart/form-data">
                @csrf
                <label for="production_year">Choose production year:</label>
                <select name="production_year" id="production_year">
                    <option value="all">all</option>
                    @if (isset($production_years))
                        @foreach($production_years as $year)
                            @foreach($year as $y)
                            <option value="{{$y}}">{{$y}}</option>
                        @endforeach
                        @endforeach
                    @endif
                </select>
                <label for="genre">Choose genre:</label>
                <select name="genre" id="genre">
                    <option value="all">all</option>
                    @if (isset($genres))
                        @foreach($genres as $genre)
                            @foreach($genre as $g)
                            <option value="{{$g}}">{{$g}}</option>
                        @endforeach
                        @endforeach
                    @endif
                </select>
                <x-primary-button type="submit" class="btn btn-sm">Filter</x-primary-button>
            </form>
        </div>
            <br>
        </div>
            <div id="anime" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                @if(isset($animes))
                    <ol>
                    <?php $counter = 1; ?>
                    @foreach($animes as $anime)
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <li>
                    <a href=<?php echo route('animes.show', [$anime->title, $anime->production_year, $anime->id])?>>
                        {{ __($counter . ". " . $anime->title) }}
                    </a>
                            <img src="{{URL::asset('/images/'.$anime->poster)}}" alt="Anime Pic" height="200" width="200">
                        </li>
                    </div><br>
                     <?php $counter += 1; ?>
                    @endforeach
                    </ol>
                @endif
            </div>
        </div>
</x-app-layout>
