<h1>
    <a id="back_anime_1" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>

@if(count($comments)==0)
    There is no comments
@else
    @foreach($comments as $comment)

        <div id="{{$comment->id . 'div'}}">
            <label style="display:block" for="{{$comment->id . "_"}}">{{$comment->name}}</label>
            <textarea style="display:block" id="{{$comment->id . "_"}}" name="text" rows="4" cols="50" disabled>{{$comment->text}}</textarea>
            <br>
            Likes: <mark id="{{$comment->id . 'likes'}}">{{$comment->likes}}</mark>
            Dislikes: <mark id="{{$comment->id . 'dislikes'}}">{{$comment->dislikes}}</mark>
            <button id="{{$comment->id . "__"}}" style="visibility: hidden" onclick="updater(this.id);">Update!</button>
            @if(Auth::user())
                <br>
                <button style="background-color: lightgrey" id="{{$comment->id}}" name="liker-{{$comment->id}}" onclick="liker(this.id);">Like</button>
                <button style="background-color: lightgrey" id="{{$comment->id}}" name="disliker-{{$comment->id}}" onclick="disliker(this.id);">Dislike</button>
                <br>
                @if(Auth::user()->id == $comment->author_id)

                    <button id="{{$comment->id}}" onclick="edit(this.id);">Edit Comment</button>
                    <button id="{{$comment->id}}" onclick="deleter(this.id);">Delete Comment</button>
                @endif
            @endif
        </div>
    @endforeach
@endif

@include('animes.comments.showjs')
