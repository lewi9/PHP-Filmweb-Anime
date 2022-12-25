<h1>
    <a id="back_anime" href="{{ route('animes.show', [$anime->title, $anime->production_year, $anime->id]) }}">Back to anime</a>
</h1>


@if(count($comments)==0)
    There is no comments
@else
    @foreach($comments as $comment)

        <div id="{{$comment->id . 'div'}}">
            <label style="display:block" for="{{$comment->id . "_"}}">{{$comment->name}}</label>
            <textarea style="display:block" id="{{$comment->id . "_"}}" name="text" rows="4" cols="50" disabled>
        {{$comment->text}}
        </textarea><br>
            Likes: <mark id="{{$comment->id . 'likes'}}">{{$comment->likes}}</mark>
            Dislikes: <mark id="{{$comment->id . 'dislikes'}}">{{$comment->dislikes}}</mark>
            <button id="{{$comment->id . "__"}}" style="visibility: hidden" onclick="updater(this.id);">Update!</button>
            @if(Auth::user())
                <br>
                <button id="{{$comment->id}}" onclick="liker(this.id);">Like</button>
                <button id="{{$comment->id}}" onclick="disliker(this.id);">Dislike</button>
                <br>
                @if(Auth::user()->id == $comment->author_id)

                    <button id="{{$comment->id}}" onclick="edit(this.id);">Edit Comment</button>
                    <button id="{{$comment->id}}" onclick="deleter(this.id);">Delete Comment</button>
                @endif
            @endif
        </div>
    @endforeach
@endif

<script>
    function edit(id)
    {
        document.getElementById(id + "_").disabled = false;
        document.getElementById(id + "__").style.visibility = 'visible';
    }
</script>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<meta name="csrf-token" content="">

<script>
    function deleter(id)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('comments.delete')}}",
            data: {
                'id':id
            },
            success: function () {
                $("#"+id+"div").remove();
            }
        });
    }

    function updater(id)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('comments.update')}}",
            data: {
                'id':id.replace('__', ''),
                'text':$('#'+id.replace('__','_')).val(),
            },
            success: function () {
                document.getElementById(id.replace('__','_')).disabled = true;
                document.getElementById(id).style.visibility = 'hidden';
                $("#"+id.replace('__', '')+"likes").text('0')
                $("#"+id.replace('__', '')+"dislikes").text('0')
            }
        });
    }

    function liker(id)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('comments.like')}}",
            data: {
                'id':id,
                @if(Auth::user())
                'user_id':{{Auth::user()->id}},
                @endif
            },
            success: function (data) {
                $("#"+id+"likes").text(data.split(',')[0])
                $("#"+id+"dislikes").text(data.split(',')[1])
            }
        });
    }

    function disliker(id)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('comments.dislike')}}",
            data: {
                'id':id,
                @if(Auth::user())
                'user_id':{{Auth::user()->id}},
                @endif
            },
            success: function (data) {
                $("#"+id+"likes").text(data.split(',')[0])
                $("#"+id+"dislikes").text(data.split(',')[1])
            }
        });
    }
</script>


