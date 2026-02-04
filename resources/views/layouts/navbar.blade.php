<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container py-1 px-3">
        <nav aria-label="breadcrumb">
            <div class="navbar-brand m-0 d-flex text-wrap align-items-center">
                <img src="{{asset('/images/logo-black.png')}}" width="50px">
                <span class="ms-2 mt-2 h5">H.M. Government of Gibraltar</span>
            </div>
        </nav>

        <div class="ms-auto d-none">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </button>
        </div>
        <div id="navbar_breadcrumb" style="margin-left: 200px;">
            @yield('breadcrumb')
        </div>
        <div class="collapse navbar-collapse show" id="navbar">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item d-flex align-items-center" style="font-weight:bold;" >
                        <i class="fa fa-user"></i>&nbsp;
                        {{ Auth::user()->rolname }}
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="dropdown-item" href="{{ route('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                    </li>


                    <li class="nav-item d-flex align-items-center">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>{{ __('Sign Out') }}
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

