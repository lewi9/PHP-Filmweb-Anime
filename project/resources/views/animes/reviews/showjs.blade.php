{{--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script>
    function rate(value)
    {
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
            type: 'get',
            url: "{{route('reviews_users.rate')}}",
            data: {
                'review_id':{{$review->id}},
                @if(Auth::id())
                'user_id': {{Auth::id() }},
                @endif
                'rating': value,
            },
            success: function (data) {
                document.getElementById("heart_"+value).checked = true;
                document.getElementById("review_rating").textContent = data.split(',')[0];
                document.getElementById("review_rates").textContent = data.split(',')[1];
            }
        });
    }
</script>


