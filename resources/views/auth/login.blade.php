<!DOCTYPE html>
<html lang='en-gb' dir="{{ Route::currentRouteName() == 'rtl' ? 'rtl' : '' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
    <title>Login - GEMS</title>
    <!-- CSS Files -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />

    @livewireStyles
</head>
<body class="landing" style="min-width:150px;">
<div class="page-header align-items-start min-vh-100">
<video id="background-video" autoplay loop muted poster="{{ asset('videos/bg-video.mp4')  }}">
    <source src="{{ asset('videos/bg-video.mp4')  }}" type="video/mp4">
</video>
 <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row signin-margin">
            <div class=" text-center py-4 pb-4 header-login">
                <img style="width: 600px;margin: auto;" src="{{asset('/images/logo_gems_transparency.png')}}">
            </div>
            <div class=" col-lg-4 my-auto mx-auto">
                <div class="card-body login_form mt-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger mb-2 text-white" role="alert" style="padding: 10px;">
                                <span class="me-2"><i class="fa-solid fa-triangle-exclamation"></i></span>{{ $errors->first('error') }}
                            </div>
                        @endif
                        <div class="input-group input-group-outline ">
                            <span style="position: absolute; left: 10px; top: 20px; transform: translateY(-50%); z-index: 9999;">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input style="margin-bottom:20px;padding-left: 40px !important;background-color: #E0E0E1;border-radius:0 !important" name="username" type="text" placeholder="Username" class="form-control @error('username') is-invalid @enderror"
                                name="username"  value="{{ old('username') }}" required autocomplete="current-username" autofocus>
                                @error('username')

                            @enderror
                        </div>

                        <div class="input-group input-group-outline ">
                            <span style="position: absolute; left: 10px; top: 20px; transform: translateY(-50%); z-index: 9999;">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input style="margin-bottom:20px;padding-left: 40px !important;background-color: #E0E0E1;border-radius:0 !important" id="password" type="password" placeholder="Password" name="password" autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                        </div>

                        <div class="text-center">
                            <button style="background-color: #AC001E;  color: #fff !important;  border: #AC001E !important;border: solid 1px #DB7083 !important;" type="submit" class="btn w-100 my-4 mb-2">{{__('LOGIN')}}</button>
                        </div>
                        <div style="cursor:pointer;color: #fff;  text-align: right;  font-size: 14px;" title="Laravel: {{ app()->version() }} - Gems: {{ Config::get('version.date_commit') }}">{{ Config::get('version.last_local_tag') }}</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{asset("/js/material-dashboard.js")}}"></script>
@livewireScripts
</body>
</html>
