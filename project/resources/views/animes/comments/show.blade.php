
<x-app-layout>
    <br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-pink-200 overflow-hidden shadow-sm sm:rounded-lg selection-div">
<div id="hide_1">

    <br>

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
        <br>
        <button class="mini-button" onclick="reset();">Clear filters</button>
        <a class="mini-button" id="back_anime_1" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">↞ Back to anime ↞</a>
    </div>
</div></div></div><br>



<div id="comments">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 com-width">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    @if($comments instanceof Illuminate\Http\Response)
        <?php echo $comments->content() ?>
    @else
        @if(count($comments)==0)
            There is no comments
        @else
            @foreach($comments as $comment)

                <div id="{{$comment->id . 'div'}}">
                    <label style="display:block" for="{{$comment->id . "_"}}">{{$comment->name}}</label>
                    <textarea id="{{$comment->id . "_"}}" name="text" rows="5" cols="60" disabled>{{$comment->text}}</textarea>

                    Likes: <mark class="L1" style="background-color: pink" id="{{$comment->id . 'likes'}}">{{$comment->likes}}</mark>
                    Dislikes: <mark  class="D1"style="background-color: pink" id="{{$comment->id . 'dislikes'}}">{{$comment->dislikes}}</mark>
                    <button id="{{$comment->id . "__"}}" style="visibility: hidden" onclick="updater(this.id);">Update!</button>
                    @if(Auth::user())
                        <br>
                        <button style="background-color: lightgrey" id="{{$comment->id}}" name="liker-{{$comment->id}}" onclick="liker(this.id);">Like</button>
                        <button style="background-color: lightgrey" id="{{$comment->id}}" name="disliker-{{$comment->id}}" onclick="disliker(this.id);">Dislike</button>
                        @if(Auth::user()->id == $comment->user_id)
                            <br>
                            <button style="background-color: hotpink; color:white; padding: 3px; border-radius: 12px; margin-top: 3px; "  id="{{$comment->id}}" onclick="edit(this.id);">Edit Comment</button>
                            <button style="background-color: hotpink; color:white; padding: 3px; border-radius: 12px; margin-top: 3px;" id="{{$comment->id}}" onclick="deleter(this.id);">Delete Comment</button>
                            <br>
                        @endif
                    @endif
                </div><br>
            @endforeach
        @endif
    @endif
</div></div></div></div>

    @include('animes.comments.showjs')
</x-app-layout>
