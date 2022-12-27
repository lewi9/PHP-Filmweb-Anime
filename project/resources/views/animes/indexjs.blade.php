<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
    $('#filter_search').on('keyup',function(){
        let value = $(this).val();
        if(value === ""){
            value = "%";
        }
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
