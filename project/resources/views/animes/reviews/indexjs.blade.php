<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
    function filter_select(val)
    {
        $.ajax({
            type: 'get',
            url: '{{route('reviews.filter')}}',
            data: {
                'filter':val,
                'anime_id': {{$anime->id}},
            },
            success: function (data) {
                $('#reviews').html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function filter_mode_select(val)
    {
        $.ajax({
            type: 'get',
            url: '{{route('reviews.filter')}}',
            data: {
                'filter_mode':val,
                'anime_id': {{$anime->id}}
            },
            success: function (data) {
                $('#reviews').html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function reset()
    {
        $.ajax({
            type: 'get',
            url: '{{route('reviews.filter')}}',
            data: {
                'filter' : 'id',
                'filter_mode' : 'asc',
                'anime_id': {{$anime->id}}
            },
            success: function (data) {
                $('#reviews').html(data);
                document.getElementById('filter').value = 'id';
                document.getElementById('filter_mode').value ='asc';
            }
        });
    }
</script>
