@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.login') }}</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="control-label" for="{{ $username }}">{{ config('backpack.base.authentication_column_name') }}</label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}">

                                @if ($errors->has($username))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first($username) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>

                            <div>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block button-85">
                                    <span>LOGIN</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
                <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
            @endif
            @if (config('backpack.base.registration_open'))
                <div class="text-center"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
            @endif
        </div>
    </div>
@endsection
<!-- HTML !-->

<style>
/* CSS */
.button-85{
padding: 0.6em 2em;
border: none;
outline: none;
color: #000000;
background: #7d8dee;
cursor: pointer;
position: relative;
z-index: 0;
border-radius: 10px;
user-select: none;
-webkit-user-select: none;
touch-action: manipulation;
}

.button-85:before {
content: "";
background: linear-gradient(
45deg,
#ff0000,
#ff7300,
#fffb00,
#48ff00,
#00ffd5,
#002bff,
#7a00ff,
#ff00c8,
#ff0000
);
position: absolute;
top: -2px;
left: -2px;
background-size: 400%;
z-index: -1;
filter: blur(5px);
-webkit-filter: blur(5px);
width: calc(100% + 4px);
height: calc(100% + 4px);
animation: glowing-button-85 20s linear infinite;
transition: opacity 0.3s ease-in-out;
border-radius: 10px;
}

@keyframes glowing-button-85 {
0% {
background-position: 0 0;
}
50% {
background-position: 400% 0;
}
100% {
background-position: 0 0;
}
}

.button-85:after {
z-index: -1;
content: "";
position: absolute;
width: 100%;
height: 100%;
background: #deecee;
left: 0;
top: 0;
border-radius: 10px;
}
</style>
