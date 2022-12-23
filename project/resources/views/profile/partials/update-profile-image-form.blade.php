<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Image Upload') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile picture.") }}
        </p>
    </header>
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <p>{{$message}}</p>
            </div>

            <img src="{{ asset('images/'.Session::get('image')) }}" />
        @endif

        <form method="POST" action="{{ route('image.store', $user->username) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" class="form-control" name="image" />
            <x-primary-button type="submit" class="btn btn-sm">Upload</x-primary-button>
        </form>
</section>
