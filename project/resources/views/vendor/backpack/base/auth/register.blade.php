@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.register') }}</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST"
                          action="{{ route('backpack.auth.register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="control-label" for="name">{{ trans('backpack::base.name') }}</label>
                            <div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="name">Username</label>

                            <div>
                                <input type="text" name="username" id="username" value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"
                                   for="{{ backpack_authentication_column() }}">{{ config('backpack.base.authentication_column_name') }}</label>

                            <div>
                                <input type="{{ backpack_authentication_column() == 'email' ? 'email' : 'text' }}"
                                       name="{{ backpack_authentication_column() }}"
                                       id="{{ backpack_authentication_column() }}"
                                       value="{{ old(backpack_authentication_column()) }}">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>

                            <div>
                                <input type="password"
                                       name="password" id="password">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"
                                   for="password_confirmation">{{ trans('backpack::base.confirm_password') }}</label>

                            <div>
                                <input type="password"
                                       name="password_confirmation" id="password_confirmation">

                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ trans('backpack::base.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
                <div class="text-center"><a
                        href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a>
                </div>
            @endif
            <div class="text-center"><a
                    href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a></div>
        </div>
    </div>
@endsection
