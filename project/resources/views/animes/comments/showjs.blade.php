<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script>
    window.addEventListener("load", (event) => {
        colors_likers();
    });

    function colors_likers() {
        @foreach($comment_like as $like)
            @if(isset($like->id))
        if('{{$like->rate}}' === 'like') {
            document.getElementsByName("{{'liker-' . $like->comment_id}}")[0].style.backgroundColor = 'lightgreen';
            document.getElementsByName("{{'disliker-'.$like->comment_id}}")[0].style.backgroundColor = 'lightgrey';
        }
        else {
            document.getElementsByName("{{'liker-' . $like->comment_id}}")[0].style.backgroundColor = 'lightgrey';
            document.getElementsByName("{{'disliker-'.$like->comment_id}}")[0].style.backgroundColor = 'pink';
        }
        @endif

        @endforeach
    }

</script>

<script>
    function edit(id)
    {
        document.getElementById(id + "_").disabled = false;
        document.getElementById(id + "__").style.visibility = 'visible';
    }
</script>



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
            success: function (data) {
                document.getElementById(id.replace('__','_')).disabled = true;
                document.getElementById(id.replace('__','_')).value = data;
                document.getElementById(id).style.visibility = 'hidden';
                $("#"+id.replace('__', '')+"likes").text('0');
                $("#"+id.replace('__', '')+"dislikes").text('0');
                document.getElementsByName('liker-'+id.replace('__', ''))[0].style.backgroundColor = 'lightgrey';
                document.getElementsByName('disliker-'+id.replace('__', ''))[0].style.backgroundColor = 'lightgrey';
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
                'status' : 'like',
            },
            success: function (data) {
                $("#"+id+"likes").text(data.split(',')[0])
                $("#"+id+"dislikes").text(data.split(',')[1])
                document.getElementsByName('liker-'+id)[0].style.backgroundColor = 'lightgreen';
                document.getElementsByName('disliker-'+id)[0].style.backgroundColor = 'lightgrey';
            }
        });
    }

    function disliker(id)
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
                'status' : 'dislike'
            },
            success: function (data) {
                $("#"+id+"likes").text(data.split(',')[0])
                $("#"+id+"dislikes").text(data.split(',')[1])
                document.getElementsByName('liker-'+id)[0].style.backgroundColor = 'lightgrey';
                document.getElementsByName('disliker-'+id)[0].style.backgroundColor = 'pink';
            }
        });
    }
</script>

