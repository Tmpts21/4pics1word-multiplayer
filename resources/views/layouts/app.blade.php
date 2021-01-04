<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
 <style>
   .custom_scroll
{
  overflow-y: scroll;
}

//custom_scroll scrollbar styling
.custom_scroll::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    opacity: 0.5;
    //background-color: #141d26;
}

.custom_scroll::-webkit-scrollbar
{
    width: 5px;
    opacity: 0.5;
    //background-color: #141d26;
}

.custom_scroll::-webkit-scrollbar-thumb
{
    border-radius: 10px;
    opacity: 0.5;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #141d26;
}
.fade-in {
opacity: 50%;
transition:opacity 0.8s
}

.img {
    width: 250px; /* You can set the dimensions to whatever you want */
    height: 180px;
    object-fit: cover;
}



/* transitions */
@keyframes chats {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}


@keyframes wrongGuess {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(0);
    }
}


@keyframes  game {
   
    0% {
        opacity : 0 ;
        transform: rotateX(-10deg);
    }
    100% {
        opacity : 1 ;

        transform: rotateX(0);
    }
}


.chats { 
    animation:  chats 0.5s ;
}

.game { 
    animation:  game 3s ;
}

.wrong { 
    animation:  wrongGuess 0.5s ;
}



 </style>
<body style = 'background-color:#141d26'>
    <div id="app">
        <nav class="navbar navbar-expand-md  font-weight-bold  shadow-sm" style = "background-color:#243447 " >
            <div class="container">
                <a class="navbar-brand text-white" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto font-weight-bold">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item font-weight-bold " href="{{ route('logout') }} "
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main  style = 'width:95%'>
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue-chat-scroll/dist/vue-chat-scroll.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
