<?php $articles = \App\Models\Article::all(); ?>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    function liker(id) {
        $.ajax({
                type: 'post',
                url: '{{URL::to('/articles/like/')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                        'article_id': id,
                        @if(Auth::user())
                        'user_id':{{Auth::user()->id}},
                        @endif
                    },
                    success: function (data) {
                        $('#'+id+'likes').text('Likes: '+data);
                    }
                });
        }

    function disliker(id) {
        $.ajax({
                type: 'post',
                url: '{{URL::to('/articles/dislike')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data:{
                        'article_id': id,
                        @if(Auth::user())
                        'user_id':{{Auth::user()->id}},
                        @endif
                    },
                    success: function (data) {
                        $('#'+id+'dislikes').text('Dislikes: '+data);
                    }
                });
        }
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("You're logged in!") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("News") }}</b>
                </div>
            </div>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                @foreach($articles as $article)
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <b>{{ __($article->title) }}</b>
                        <img src="{{URL::asset('/images/'. $article->photo)}}" alt="Anime Pic" height="200" width="200">
                        <div>
                            {{__($article->text)}}
                            <div id={{$article->id . "likes"}}>Likes: {{__($article->likes)}}</div>
                            <div id={{$article->id . "dislikes"}}>Dislikes: {{__($article->dislikes)}}</div>
                            @if (Auth::user())
                                <div>
                            <x-primary-button id="{{$article->id}}" onclick="liker(this.id)">
                                Like
                            </x-primary-button>
                            <x-primary-button id="{{$article->id}}" onclick="disliker(this.id)">
                                Dislike
                            </x-primary-button>
                                </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
