<h1>
    <a id="back_anime_1" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>

<div id="filter_form">
    <form>
        @csrf
        <label for="filter">Choose a filter type:</label>
        <select name="filter" id="filter" onchange="filter_select(this.value);">
            <option value="id" @if (session('comments_filter') == "id") selected @endif>time</option>
            <option value="likes" @if (session('comments_filter') == "likes") selected @endif>like</option>
            <option value="dislikes" @if (session('comments_filter') == "dislikes") selected @endif>dislikes</option>
        </select>
        <label for="filter_mode">Choose a ascend or descend filter mode:</label>
        <select name="filter_mode" id="filter_mode" onchange="filter_mode_select(this.value);">
            <option value="asc" @if (session('comments_filter_mode') == "asc") selected @endif>ascending</option>
            <option value="desc" @if (session('comments_filter_mode') == "desc") selected @endif>descending</option>
        </select>
    </form>
    <button onclick="reset();">Clear filters</button>
</div>

<div id="comments">
    @if($comments instanceof Illuminate\Http\Response)
        <?php echo $comments->content() ?>
    @else
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
    @endif
</div>
@include('animes.comments.showjs')
